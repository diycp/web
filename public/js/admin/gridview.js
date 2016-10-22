/**
 * 系统集成js
 */
$(function() {
    var GridView = function(el, option) {
        this.$table = $(el);

        if (this.$table.length == 0) {
            return;
        }

        this.$toolbar = $(this.$table.data('toolbar'));
        this.$form = this.$toolbar.find('.edit-form, .search-form');
        this.bootstrapTable = null;
        this.queryParams = null;
        this.currentRow = null;
        this.uniqueId = 'id';
        this.module = this.$toolbar.data('module');
        // console.log(this.module)

        this.pagination = this.$table.data('pagination') == false ? false : true;
        this.sidePagination = this.$table.data('sidePagination') || "server";
        this.clientSort = this.$table.data('clientSort') == false ? false : true;
        this.pageSize = this.$table.data('pageSize') || 50;
        this.clickToSelect = this.$table.data('clickToSelect') == false ? false : true;
        this.showRefresh = this.$table.data('showRefresh') == false ? false : true;
        this.$table.data('gridview', this);
        this.enabledEdit = this.$table.data('edit') || false;
        this.init();
    };

    GridView.prototype.init = function() {
        this.initTable();
        this.initForm();
        this.initToolbar();
    };

    GridView.prototype.initTable = function() {
        zh_table();
        var $this = this;
        $this.$table.bootstrapTable({
            striped: false, // 隔行换色
            uniqueId: 'id',
            showRefresh: $this.showRefresh,
            clientSort: $this.clientSort,
            pagination: $this.pagination,
            classes: 'table table-hover table-no-bordered',
            sidePagination: $this.sidePagination,
            pageSize: $this.pageSize,
            //clickToSelect: $this.clickToSelect,
            queryParams: function(params) {
                params = $.extend(params, $this.queryParams);

                if ($this.clientSort) {
                    delete params.sort;
                    delete params.order;
                }

                if ($this.pagination == 'client') {

                }
                return params;
            },
            onAll: function(name, args) {
                //$table.trigger('all', [name, args]);
                return false;
            },
            onClickCell: function(field, value, row, $element) {
                //$table.trigger('clickCell', [field, value, row, $element]);
                return false;
            },
            onDblClickCell: function(field, value, row, $element) {
                //$table.trigger('dblClickCell', [field, value, row, $element]);
                return false;
            },
            onClickRow: function(row, $element) {
                $this.scrollPosition = $this.$table.bootstrapTable('getScrollPosition');
                var result = $this.$table.triggerHandler('clickRow', [row, $this]);
                if (result !== false && $this.enabledEdit) {
                    $this.editRow(row);
                }
                $element.addClass('info').siblings().removeClass('info');
                $this.currentRow = row;
            },
            onDblClickRow: function(item, $element) {
                //$table.trigger('dblClickRow', [item, $element]);
                return false;
            },
            onSort: function(name, order) {
                //$table.triggerHandler('sort', [name, order]);
            },
            onCheck: function(row) {
                $this.$table.trigger('check', [row, $this]);
                return false;
            },
            onUncheck: function(row) {
                $this.$table.trigger('uncheck', [row, $this]);
                return false;
            },
            onCheckAll: function(rows) {
                //$table.trigger('checkAll', [rows]);
                return false;
            },
            onUncheckAll: function(rows) {
                //$table.trigger('uncheckAll', [rows]);
                return false;
            },
            onCheckSome: function(rows) {
                //$table.trigger('checkSome', [rows]);
                return false;
            },
            onUncheckSome: function(rows) {
                //$table.trigger('uncheckSome', [rows]);
                return false;
            },
            onLoadSuccess: function(data) {
                $this.resetView();
                return false;
            },
            onLoadError: function(status) {
                //$table.trigger('loadError', [status]);
                return false;
            },
            onColumnSwitch: function(field, checked) {
                //$table.trigger('columnSwitch', [field, checked]);
                return false;
            },
            onPageChange: function(number, size) {
                //$table.trigger('pageChange', [number, size]);
                return false;
            },
            onSearch: function(text) {
                //$table.trigger('search', [text]);
                return false;
            },
            onToggle: function(cardView) {
                //$table.trigger('toggle', [cardView]);
                return false;
            },
            onPreBody: function(data) {
                //$table.trigger('preBody', [data]);
                return false;
            },
            onPostBody: function() {
                //$table.trigger('postBody');
                return false;
            },
            onPostHeader: function() {
                //$table.trigger('postHeader');
                return false;
            },
            onExpandRow: function(index, row, $detail) {
                //$table.trigger('expandRow', [index, row, $detail]);
                return false;
            },
            onCollapseRow: function(index, row) {
                //$table.trigger('collapseRow', [index, row]);
                return false;
            },
            onRefreshOptions: function() {
                alert();
            }
        });

        $this.bootstrapTable = $this.$table.data('bootstrap.table');
        $this.resetView();

        //改变窗口大小时调用该函数
        $(window).resize(function() {
            $this.resetView();
        });

        $this.uniqueId = $this.bootstrapTable.options.uniqueId;
    };

    GridView.prototype.initForm = function() {
        var $this = this;
        if ($this.$form.length == 0) {
            return;
        }

        if (typeof $.fn.validate == 'undefined') {
            win.getScript('/js/admin/jquery.validate.min.js', function() {
                $this.initForm();
            });
            return;
        }

        zh_validator();
        $this.$form.validate({
            errorClass: "help-inline",
            errorElement: "span",
            ignore: ".ignore",
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            focusInvalid: false,
            focusCleanup: true,
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group:eq(0)').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group:eq(0)').removeClass('error');
            },
            errorPlacement: function(error, element) {
                error.appendTo(element.parents('.controls:eq(0)'));
            },
            submitHandler: function() {
                return false;
            }
        });
    };

    GridView.prototype.initToolbar = function() {
        var $this = this;
        this.$form.on('submit', function() {
            var row = $this.getFormValue();
            $this.queryParams = row;
            $this.bootstrapTable.options.pageNumber = 1;
            $this.$table.bootstrapTable('refresh');
            return false;
        });

        this.$toolbar.find('>.btn-group>button[data-name]').on('click', function() {
            // 要执行的事件名称
            var $btn = $(this);
            // console.log($btn)
            var eventName = $btn.data('name');
            // console.log(eventName)

            var params = {
                url: $btn.data('eventValue') == '' ? ($this.module + '/' + eventName) : $btn.data('eventValue'),
                event_type: $btn.data('eventType'),
                event_value: $btn.data('eventValue'),
                target: $btn.data('target'),
                text: this.innerText
            };
            // console.log(params)

            //事件类型 1. 自定义 2.视图 3.默认
            if (params.event_type == 'custom') { // 自定义事件
                return $this.$table.triggerHandler(eventName, [$this, params]);
            } else if (params.event_type == 'view') { // 打开网址
                params.data = {};

                if (eventName.substr(0, 4) == 'edit' || eventName.substr(0, 6) == 'update') {
                    if ($this.currentRow == null) {
                        return alertMsg('请先选择要编辑的数据！', 'warning');
                    }
                    if (undefined == $this.currentRow[$this.uniqueId]) {
                        params.data[$this.uniqueId] = $this.currentRow.order_no;
                        //console.log($this.currentRow);return;
                    } else {
                        params.data[$this.uniqueId] = $this.currentRow[$this.uniqueId];
                    }

                    if (params.event_value == '') {
                        params.url += '?' + $this.uniqueId + '=' + $this.currentRow[$this.uniqueId];
                    }
                }

                var result = $this.$table.triggerHandler(eventName, [$this, params]);
                if (result === false) {
                    return;
                }
                //打开方式 1.模态框 2.本页打开 3.在新窗口打开
                if (params.target == 'modal') {
                    $this.loadModal(params.url, params.data);
                } else if (params.target == 'self' || params.target == '') {
                    window.location.href = params.url;
                } else if (params.target == '_blank') {
                    window.open(params.url);
                } else {
                    var $container = $(params.target);
                    $container.load(params.url, function() {
                        win.init($container);
                        $container.find('table[data-toggle="gridview"]').gridView();;
                    });
                }
                return;
            } else if (params.event_type == 'javascript') { // 打开网址
                return $('html').append('<script type="text/javascript">' + params.event_value + '</script>');
            }

            if (eventName.substr(0, 6) == 'search') { // 搜索
                var row = $this.getFormValue();
                $this.queryParams = row;
                $this.bootstrapTable.options.pageNumber = 1;
                $this.$table.bootstrapTable('refresh');
            } else if (eventName.substr(0, 3) == 'add' || eventName.substr(0, 6) == 'insert') { // 添加

                if ($this.$form.length == 0) {
                    return $this.$table.triggerHandler(eventName, [$this, params]);
                }
                // 验证数据
                if (!$this.$form.valid()) {
                    return;
                }

                params.row = $this.getFormValue($this.$form);
                delete params.row[$this.uniqueId]; // 添加需要清空主键

                var result = $this.$table.triggerHandler(eventName, [$this, params]);
                if (result === false) {
                    return;
                }

                alertConfirm({
                    title: '提示',
                    content: '确定' + params.text + '吗？',
                    ok: function() {
                        $.ajax({
                            url: params.url,
                            data: params.row,
                            type: 'post',
                            dataType: 'json',
                            success: function(data) {
                                $this.editRow(); // 重置表单
                                if (!win.empty(data)) {
                                    params.row = $.extend(params.row, data);
                                }

                                if (win.empty(params.row[$this.uniqueId])) {
                                    $this.refresh();
                                } else {
                                    var newData = { index: 0, row: params.row };
                                    $this.$table.bootstrapTable('insertRow', newData);
                                    $this.resetView();
                                }
                            }
                        });
                    }
                });
            } else if (eventName.substr(0, 4) == 'edit' || eventName.substr(0, 6) == 'update') { // 编辑
                if ($this.$form.length == 0) {
                    return $this.$table.triggerHandler(eventName, [$this, params]);
                }

                if ($this.currentRow == null) {
                    return alertMsg('请先选择要编辑的数据！', 'warning');
                }

                params.row = $this.getFormValue($this.$form);

                var result = $this.$table.triggerHandler(eventName, [$this, params]);
                if (result === false || !$this.$form.valid()) {
                    return;
                }

                $.ajax({
                    url: params.url,
                    data: params.row,
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
                        // 重置表单
                        $this.editRow();

                        // 获取当前数据所在行
                        if (!win.empty(data)) { params.row = data; }
                        var data_index = $this.bootstrapTable.$body.find('tr[data-uniqueid="' + params.row[$this.uniqueId] + '"]').attr('data-index');

                        // 更新行数据
                        var newData = { index: data_index, row: params.row };
                        $this.$table.bootstrapTable('updateRow', newData);

                        // 重置大小
                        $this.resetView();
                    }
                });
            } else if (eventName.substr(0, 6) == 'delete') {
                var rows = $this.$table.bootstrapTable('getSelections'); // 当前页被选中项(getAllSelections 所有分页被选中项)
                if (rows.length == 0) {
                    alertMsg('请勾选要删除的数据', 'warning');
                    return;
                }

                var params = {
                    rows: rows,
                    length: rows.length,
                    url: $this.module + '/' + eventName,
                    backdrop: true,
                    title: '提示',
                    message: '确定要删除选中的' + rows.length + '项吗？',
                    okValue: '确定',
                    cancelValue: '取消',
                    ajaxMsg: '正在删除中...',
                    data: null,
                    ok: function() {
                        var post_data = {};

                        var uniqueId = [];
                        var needRestForm = false;
                        var editUniqueId = $this.$form.find('input[name="' + $this.uniqueId + '"]').val();
                        for (var i in params.rows) {
                            uniqueId.push(params.rows[i][$this.uniqueId]);
                            // 判断当前编辑的数据是否在删除数组中
                            if (editUniqueId == params.rows[i][$this.uniqueId]) {
                                needRestForm = true;
                            }
                        }

                        if (params.data == null) {
                            post_data[$this.uniqueId] = uniqueId.join(',');
                        } else {
                            post_data = params.data;
                        }

                        // 请求服务器删除数据
                        $.ajax({
                            url: params.url,
                            dataType: 'json',
                            data: post_data,
                            waitting: params.ajaxMsg,
                            type: 'post',
                            success: function(ajaxData) {
                                if (needRestForm) { $this.editRow(); }
                                ajaxData.deletedRows = params.rows;
                                // 通知删除成功
                                var result = $this.$table.triggerHandler('deleted', [ajaxData, 'success']);
                                if (result === false) {
                                    return;
                                }

                                $this.$table.bootstrapTable('remove', { field: 'id', values: uniqueId });

                                if ($this.bootstrapTable.data.length == 0 && $this.bootstrapTable.options.sidePagination == 'server') {
                                    $this.$table.bootstrapTable('refresh');
                                } else {
                                    $this.resetView();
                                }
                            },
                            error: function(ajaxData) {
                                ajaxData.deletedRows = params.rows;
                                $this.$table.triggerHandler('deleted', [ajaxData, 'error']);
                            }
                        });
                    },
                    cancel: function() {}
                };

                // 通知我要删除
                var result = $this.$table.triggerHandler('delete', [$this, params]);
                if (result === false) {
                    return;
                }

                // 弹出删除提示
                alertConfirm({
                    title: params.title,
                    content: params.message,
                    okValue: params.okValue,
                    cancelValue: params.cancelValue,
                    ok: params.ok,
                    cancel: params.cancel,
                    backdrop: params.backdrop
                });
            } else {
                $table.triggerHandler(eventName, [$this, params]);
            }
        });
    };

    GridView.prototype.resetForm = function() {
        if (this.$form.length == 0) {
            return;
        }

        this.$form[0].reset();
        /*
		this.$form.find('input[type=hidden]').each(function(i, item){
    		item.value = '';
    	});
    	*/
    };

    GridView.prototype.editRow = function(row) {
        this.currentRow = row;
        if (this.$form.length == 0) {
            return;
        }

        var uniqueId = this.$form.find('input[name="' + this.uniqueId + '"]').val();

        if (row == null) {
            this.resetForm();
            return;
        }

        if (uniqueId == row[this.uniqueId]) {
            return;
        }

        this.resetForm();

        for (var i in row) {
            var $element = this.$form.find('input[name="' + i + '"]');
            if ($element.length == 0) {
                continue;
            }

            if ($element[0].type == 'radio' || $element[0].type == 'checkbox') {
                $element.each(function(e_i, item) {
                    if (row[i] instanceof Array) {
                        item.checked = row[i].indexOf(item.value) != -1;
                    } else {
                        item.checked = item.value == row[i];
                    }
                });
            } else {
                $element.val(row[i]);
            }
        }
    };

    GridView.prototype.resetView = function(height) {
        /*
        if(this.$table.attr('data-height') != undefined){
        	return;
        }
        if(height == undefined){
        	height = $(window).height() - this.bootstrapTable.$tableHeader.offset().top;
        	
        	if(this.bootstrapTable.options.heightCut != undefined && this.bootstrapTable.options.heightCut != ''){
        		if(!isNaN(this.bootstrapTable.options.heightCut)){
        			height -= parseInt(this.bootstrapTable.options.heightCut);
        		}else{
        			height -= $(this.bootstrapTable.options.heightCut).outerHeight();
        		}
        	}
        }
        this.$table.bootstrapTable('resetView',{height: height});
        */

        //this.$table.bootstrapTable('resetView');
        if (this.$table.data('height') != undefined) {
            this.$table.bootstrapTable('resetView');
        }

        if (this.currentRow != null) {
            this.$table.bootstrapTable('scrollTo', this.scrollPosition);
            this.$table.find('tr[data-uniqueid="' + this.currentRow[this.uniqueId] + '"]').addClass('info').siblings().removeClass('info');
        }
    };

    GridView.prototype.refresh = function() {
        this.$table.bootstrapTable('refresh');
    };

    GridView.prototype.loadModal = function(url, data) {
        var $this = this;
        $.ajax({
            url: url,
            waitting: true,
            dataType: 'html',
            data: data,
            waitting: '正在加载，请稍后...',
            success: function(html) {
                var $html = $('<div>' + html + '</div>');

                var $modal = $html.find('.modal:eq(0)');
                if ($modal.length == 0) {
                    alertMsg(html, 'warning');
                    return;
                }

                $html.appendTo('body');
                win.init($html);
                $modal.modal().show();

                var action = '';
                var $form = $html.find('form');
                $modal.on('hide', function() {
                    if ($form.length > 0 && $form.data('submited') == true) {
                        if (action == '') {
                            $this.$table.bootstrapTable('refresh');
                        } else if (action == 'add') {
                            $this.resetView();
                        }
                    }

                    $html.remove();
                });

                if ($form.length > 0 && $form.attr('data-submit') == 'ajax') {
                    $form.on('ajaxSubmit', function(e, data) {
                        var row = $this.getFormValue($form);
                        if (!win.empty(data)) {
                            row = $.extend(row, data);
                        }

                        if (!win.empty(row[$this.uniqueId])) {
                            if ($form.data('success') == 'refresh') {
                                $form.data('success', null);
                            } else {
                                // 获取当前数据所在行
                                var data_index = $this.bootstrapTable.$body.find('tr[data-uniqueid="' + row[$this.uniqueId] + '"]').attr('data-index');
                                if (data_index == undefined) { // 添加数据
                                    action = 'add';
                                    $this.$table.bootstrapTable('insertRow', { index: 0, row: row });
                                } else { // 更新行数据
                                    action = 'edit';
                                    $this.$table.bootstrapTable('updateRow', { index: data_index, row: row });
                                }
                            }
                        }
                    });
                }
            }
        });
    };

    GridView.prototype.showModal = function(modal) {
        $this = this;
        var $modal = $(modal);

        $modal.modal().show();
        $modal.on('hide', function() {
            var $form = $modal[0].nodeName == 'FORM' ? $modal : $modal.find('form');

            if ($form.length > 0 && $form.data('submited') == true) {
                $form.data('submited', false);
                $this.$table.bootstrapTable('refresh');
            }
        });
    };

    GridView.prototype.getFormValue = function(selector) {
        var $form = selector == undefined ? this.$form : $(selector);
        if ($form.length == 0) {
            return;
        }

        var row = {};
        var serializeArray = $form.serializeArray(),
            name;

        // 仅支持到一位数组
        $.each(serializeArray, function(i, item) {
            name = item.name.substr(5, item.name.indexOf(']') - 5);
            if (name == '') {
                row[item.name] = item.value;
            } else {
                if (row[name] !== undefined) {

                    // 保存数组形式
                    if (!row[name].push) {
                        row[name] = [row[name]];
                    }
                    row[name].push(item.value || '');
                } else {
                    row[name] = item.value || '';
                }
            }
        });

        for (var i in row) {
            if (row[i] instanceof Array) {
                row[i] = row[i].join(',');
            }
        }
        return row;
    };

    GridView.prototype.current = function() {
        return this.currentRow;
    }


    new GridView('table[data-toggle="gridview"]');

    $.fn.gridView = function(option, params) {
        var $gridview = this.data('gridview');
        if (typeof option == 'string') {
            if ($gridview == undefined) {
                return;
            }

            return $gridview[option](params);
        }

        if ($gridview != undefined) {




            return;
        }

        if (this.length > 0) {
            new GridView(this, option);
        }
        return this;
    };
});
