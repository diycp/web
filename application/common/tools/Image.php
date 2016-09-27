<?php
namespace app\common\tools;

/**
 * ------------------------------------------
 * 图片处理类 (GD2库)
 * ------------------------------------------
 *
 * 根据实际需求设计的图片处理类, “居中剪裁”通常使用在用户头像、缩略图等场景, “等比缩放”通常用来压缩图片
 *
 * 1. 居中剪裁 (宽高自动)
 * 2. 等比缩放 (宽高自动, 源图小于宽高的不做缩放)
 * 3. 创建缩略图
 * 4. 如果操作网络图片, 会在根目录生成"tmp.jpg" (用于测试)
 *
 */
class Image
{
    protected $sourceImage = ''; // 源文件路径
    protected $width       = ''; // 要设置的宽度
    protected $height      = ''; // 要设置的高度
    protected $createThumb = false; // 是否创建缩略图
    protected $thumbMarker = '_thumb'; // 缩略图后缀

    /**
     * [__construct description] 
     * @param    array                    $props [description]
     */
    public function __construct($props = array())
    {
        if (count($props) > 0) {
            $this->initialize($props);
        }
    }

    /**
     * 初始化配置 
     * @param    [type]                   $props [description]
     * @return   [type]                          [description]
     */
    public function initialize($props)
    {
        $this->clear(); // 清除之前的配置
        $this->sourceImage = !isset($props['sourceImage']) ? $this->sourceImage : $props['sourceImage'];
        $this->width       = !isset($props['width']) ? $this->width : $props['width'];
        $this->height      = !isset($props['height']) ? $this->height : $props['height'];
        $this->createThumb = !isset($props['createThumb']) ? $this->createThumb : $props['createThumb'];
        $this->thumbMarker = !isset($props['thumbMarker']) ? $this->thumbMarker : $props['thumbMarker'];
    }

    /**
     * 清除配置 
     * @return   [type]                   [description]
     */
    protected function clear()
    {
        $this->sourceImage = '';
        $this->width       = '';
        $this->height      = '';
        $this->createThumb = false;
        $this->thumbMarker = '_thumb';
    }

    /**
     * 等比缩放 
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function resize($value = '')
    {
        $sourcePath   = $this->sourceImage;
        $targetWidth  = $this->width;
        $targetHeight = $this->height;
        $sourceInfo   = getimagesize($sourcePath);
        $sourceWidth  = $sourceInfo[0];
        $sourceHeight = $sourceInfo[1];
        $sourceMime   = $sourceInfo['mime'];
        switch ($sourceMime) {
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            default:
                $sourceImage = imagecreatefromjpeg($sourcePath); // 兼容app, 许多app上传的图片无mime信息
                break;
        }

        $widthRatio  = $targetWidth / $sourceWidth;
        $heightRatio = $targetHeight / $sourceHeight;
        // 源图宽高均小于要设置的值
        if ($widthRatio >= 1 && $heightRatio >= 1) {
            $targetImage = $sourceImage;
        } else {
            // 根据缩放倍率小的宽或者高缩放
            if ($widthRatio < $heightRatio) {
                $zoom_width  = $targetWidth;
                $zoom_height = $sourceHeight * ($targetWidth / $sourceWidth);
            } else {
                $zoom_height = $targetHeight;
                $zoom_width  = $sourceWidth * ($targetHeight / $sourceHeight);
            }
            // 声明图片资源
            $targetImage = imagecreatetruecolor($zoom_width, $zoom_height);
            // 缩放
            imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $zoom_width, $zoom_height, $sourceWidth, $sourceHeight);
        }
        // 图片地址为url
        if (strpos($sourcePath, 'http') !== false) {
            imagejpeg($targetImage, $_SERVER['DOCUMENT_ROOT'] . '/tmp.jpg');
        } else {
            if ($this->createThumb) {
                $sourcePath = str_replace('.', $this->thumbMarker . '.', $sourcePath);
            }
            imagejpeg($targetImage, $sourcePath);
        }

        //销毁资源
        imagedestroy($sourceImage);
        @imagedestroy($targetImage);
        $this->clear();
    }

    /**
     * 居中剪裁 
     * @return   [type]                   [description]
     */
    public function crop()
    {
        $sourcePath   = $this->sourceImage;
        $targetWidth  = $this->width;
        $targetHeight = $this->height;
        $sourceInfo   = getimagesize($sourcePath);
        $sourceWidth  = $sourceInfo[0];
        $sourceHeight = $sourceInfo[1];
        $sourceMime   = $sourceInfo['mime'];
        $sourceRatio  = $sourceHeight / $sourceWidth;
        $targetRatio  = $targetHeight / $targetWidth;
        if ($sourceRatio > $targetRatio) {
            // 源图过高
            $croppedWidth  = $sourceWidth;
            $croppedHeight = $sourceWidth * $targetRatio;
            $source_x      = 0;
            $source_y      = ($sourceHeight - $croppedHeight) / 2;
        } elseif ($sourceRatio < $targetRatio) {
            // 源图过宽
            $croppedWidth  = $sourceHeight / $targetRatio;
            $croppedHeight = $sourceHeight;
            $source_x      = ($sourceWidth - $croppedWidth) / 2;
            $source_y      = 0;
        } else {
            // 源图适中
            $croppedWidth  = $sourceWidth;
            $croppedHeight = $sourceHeight;
            $source_x      = 0;
            $source_y      = 0;
        }
        switch ($sourceMime) {
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            default:
                $sourceImage = imagecreatefromjpeg($sourcePath); // 兼容app, 许多app上传的图片无mime信息
                break;
        }
        // 声明图片资源
        $targetImage   = imagecreatetruecolor($targetWidth, $targetHeight);
        $cropped_image = imagecreatetruecolor($croppedWidth, $croppedHeight);
        // 裁剪
        imagecopy($cropped_image, $sourceImage, 0, 0, $source_x, $source_y, $croppedWidth, $croppedHeight);
        // 缩放
        imagecopyresampled($targetImage, $cropped_image, 0, 0, 0, 0, $targetWidth, $targetHeight, $croppedWidth, $croppedHeight);
        // 图片地址为url
        if (strpos($sourcePath, 'http') !== false) {
            imagejpeg($targetImage, $_SERVER['DOCUMENT_ROOT'] . '/tmp.jpg');
        } else {
            if ($this->createThumb) {
                $sourcePath = str_replace('.', $this->thumbMarker . '.', $sourcePath);
            }
            imagejpeg($targetImage, $sourcePath);
        }
        // 销毁资源
        imagedestroy($sourceImage);
        imagedestroy($targetImage);
        imagedestroy($cropped_image);
        $this->clear();
    }
}
