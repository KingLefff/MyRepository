"use strict";

var underscore = _.noConflict();

$(document).ready(function() {
    
    $(".tableDiv").each(function() {
        var Id = $(this).get(0).id;
        var maintbheight = 555;
        var maintbwidth = 911;

        $("#" + Id + " .FixedTables").fixedTable({
                                                     width: maintbwidth,
                                                     height: maintbheight,
                                                     fixedColumns: 1,
                                                     // header style
                                                     classHeader: "fixedHead",
                                                     // footer style
                                                     classFooter: "fixedFoot",
                                                     // fixed column on the left
                                                     classColumn: "fixedColumn",
                                                     // the width of fixed column on the left
                                                     fixedColumnWidth: 300,
                                                     // table's parent div's id
                                                     outerId: Id,
                                                     // tds' in content area default background color
                                                     Contentbackcolor: "#FFFFFF",
                                                     // tds' in content area background color while hover.
                                                     Contenthovercolor: "#99CCFF",
                                                     // tds' in fixed column default background color
                                                     fixedColumnbackcolor:"#1dc0ff",
                                                     // tds' in fixed column background color while hover.
                                                     fixedColumnhovercolor:"#99CCFF"
                                                 });
    });
});

(function ($) {

    $('#Next').click(function(){
        var t1 = $('select#AllTable').val();
        var t2 = $('select#TwoTable').val();
        var t3 = $('select#ThreeTable').val();
        var er = $('#alert-er');
        var no_error = true;

        if( t1 == "" || t2 == "" || (t3 == "" || t3 == 0)) {
            no_error = false;
            var interval = setInterval(function () {
                if (!er.is(':visible')) {
                    er.show();
                    hideBox();
                    clearInterval(interval);
                }
            }, 500);
        }else no_error = true;
        
        if(no_error) {
//            return true;
            document.location.href = "?r=OutputTable/preview";
        }
        return false;
    });

    function hideBox() {
        setTimeout(function () {
            $('#alert-er').hide();
        }, 3000);
    }

})(jQuery);

(function outputtable ($, _) {
    function OutputTable () {
        var self = this;

        self.init = function () {
            self.cacheElements();
            self.bindEvents();
            self.initAjax();
        };

        self.cacheElements = function () {
            self.template = '#dropList-template';
            self.$containerExp = 'div#exp-dropList';
            self.$containerWhere = 'div#WhereData';
            self.$AddWhere = 'a#AddWhere';
            self.$add = "a#AddSlashData";
            self.$del = "a#DelSlashData";
            self.$head = 'Header';
            self.$column = 'Column';
            self.$center = 'Center';
            self.$WC1 = '#WhereCondition_1';
            self.$WC2 = '#WhereCondition_2';
            self.$WO = '#WhereOperator';
            self.$operator = 'input.operator_a_o';

            self.$sucessBox = $('#outputTable-success-box');
            self.$Obj_containerExp = $('div#exp-dropList');
            self.$Obj_containerWhere = $('div#WhereData');
            self.$Obj_add = $("a#AddSlashData");
            self.$Obj_del = $("a#DelSlashData");
            self.$Obj_AddWhere = $('a#AddWhere');

        };

        self.bindEvents = function () {
            self.$Obj_add.on('click', self.add);
            self.$Obj_del.on('click',self.del);
            self.$Obj_AddWhere.on('click',self.addW);
            self.$Obj_containerWhere.on('click', '#WDel', self.remove);
            self.$Obj_containerWhere.on('click', '#WApply', self.apply);
            self.$Obj_containerWhere.on('change',self.$WC1,self.change);
            self.$Obj_containerWhere.on('change',self.$operator,self.choice);
        };

        self.initAjax = function () {
            $(document).ajaxError(function (event, request, settings) {
                switch (request.status) {
                    case 403:
                        window.location.reload();
                        break;
                }
            });
        };
//добавление блока условия
        self.addW = function (e) {
            var clas = this.className;
            $(self.$containerWhere+'.'+clas).append($(self.template+'.'+clas).html());
            $(this).css({"display":'none'});
        };
//добавление доп.полей
        self.add = function (e) {
//            self.$containerExp.append(self.template);
//            var $button = $(e.target);
            switch (this.className){
                case self.$head:{
                    if($(self.$containerExp+self.$head+"-1").is(":hidden")){
                        $(self.$containerExp+self.$head+"-1").css({"display": 'block'});
                        $(self.$del+'.'+self.$head).css({"display": 'block'});
                    }else{
                        $(self.$containerExp+self.$head+"-2").css({"display": 'block'});
                        $(this).css({'display':'none'});
                    }
                    break;
                }
                case self.$column:{
                    if($(self.$containerExp+self.$column+"-1").is(":hidden")){
                        $(self.$containerExp+self.$column+"-1").css({"display": 'block'});
                        $(self.$del+'.'+self.$column).css({"display": 'block'});
                    }else{
                        $(self.$containerExp+self.$column+"-2").css({"display": 'block'});
                        $(this).css({'display':'none'});
                    }
                    break;
                }
                case self.$center:{
                    if($(self.$containerExp+self.$center+"-1").is(":hidden")){
                        $(self.$containerExp+self.$center+"-1").css({"display": 'block'});
                        $(self.$del+'.'+self.$center).css({"display": 'block'});
                    }else{
                        $(self.$containerExp+self.$center+"-2").css({"display": 'block'});
                        $(this).css({'display':'none'});
                    }
                    break;
                }
            }
            return false;
        };
//удаление доп.полей
        self.del = function (e) {

//            var $button = $(e.target);

            switch (this.className){
                case self.$head:{
                    if(!$(self.$containerExp+self.$head+"-2").is(":hidden")){

                        $(self.$containerExp+self.$head+"-2").css({"display": 'none'});
                        $(self.$containerExp+self.$head+"-2 >select").val("");

                        $(self.$add+'.'+self.$head).css({"display": 'block'});
                    }else{
                        $(self.$containerExp+self.$head+"-1").css({"display": 'none'});
                        $(this).css({'display':'none'});
                        $(self.$containerExp+self.$head+"-1 >select").val("");
                        self.unset(self.$head);
                    }
                    break;
                }
                case self.$column:{
                    if(!$(self.$containerExp+self.$column+"-2").is(":hidden")){

                        $(self.$containerExp+self.$column+"-2").css({"display": 'none'});
                        $(self.$containerExp+self.$column+"-2 >select").val("");

                        $(self.$add+'.'+self.$column).css({"display": 'block'});
                    }else{
                        $(self.$containerExp+self.$column+"-1").css({"display": 'none'});
                        $(this).css({'display':'none'});
                        $(self.$containerExp+self.$column+"-1 >select").val("");
                        self.unset(self.$column);
                    }
                    break;
                }
                case self.$center:{
                    if(!$(self.$containerExp+self.$center+"-2").is(":hidden")){

                        $(self.$containerExp+self.$center+"-2").css({"display": 'none'});
                        $(self.$containerExp+self.$center+"-2 >select").val("");

                        $(self.$add+'.'+self.$center).css({"display": 'block'});
                    }else{
                        $(self.$containerExp+self.$center+"-1").css({"display": 'none'});
                        $(this).css({'display':'none'});
                        $(self.$containerExp+self.$center+"-1 >select").val("");
                        self.unset(self.$center);
                    }
                    break;
                }
            }

            return false;
        };
//применение блока условий
        self.apply = function (e) {

            var $button = $(e.target);

            var $box = $button.parent('.where-box');
            var $class = $box.parent('#WhereData');
            $class = $class[0].className;
            var id = $box.data('id');
            var $tag = $box.find(self.$operator + ':checked').val();

            var data = {
                class: $class,
                condition_1: $box.find(self.$WC1).val(),
                operator: $box.find(self.$WO).val(),
                condition_2: $box.find(self.$WC2).val(),
                operand: $tag
            };

            if((data.condition_1) && (data.condition_2) && (data.operator)) {
                if (!id) {
                    self.create(data, function (err, data) {
                        if (err) {
                            $box.addClass('error');
                        }
                        else {
                            $box.removeClass('error');
                            self.showSuccessBox();
                            $box.data('id', data.id);
                        }
                    });
                }
                else {
                    self.update(id, data, function (err, data) {
                        if (err) {
                            $box.addClass('error');
                        }
                        else {
                            self.showSuccessBox();
                            $box.removeClass('error');
                        }
                    });
                }

                if ($tag != 'no')
                    $(self.$containerWhere+'.'+$class).append($(self.template+'.'+$class).html());

            }else {
                var interval = setInterval(function () {
                    if (!$('#alert-er').is(':visible')) {
                        $('#alert-er').show();
                        setTimeout(function () {
                            $('#alert-er').hide();
                        }, 3000);
                        clearInterval(interval);
                    }
                }, 500);

                self.blink($box);
            }

            return false;
        };
//удаление блока условий
        self.remove = function (e) {
            var $button = $(e.target);
            var $box = $button.parent('.where-box');
            var $class = $box.parent('#WhereData');
            $class = $class[0].className;
            var id = $box.data('id');
            $button = false;

            if (!id) {
                $box.remove();
                $button = true;
            } else {
                if (confirm('Вы уверены, что хотите удалить запись?')) {
                    $.ajax({
                               url: '?r=OutputTable/Delete' + '&id=' + id+ '&class='+$class,
                               type: 'DELETE',
                               dataType: 'json'
                           }).done(function (data) {
                        $box.remove();
                    }).fail(function (jqXHR) {
                        console.error('OutputTable: remove:', jqXHR.statusText, id);
                    });
                    $button = true;
                }
            }

            if($button) $(self.$AddWhere+'.'+$class).css({'display':'block'});

            return false;
        };
//при изменении поля в блоке условий, обновляется с чем сравнивается
        self.change = function (e) {
            var $tag = $(e.target);

            var $box = $tag.parent('.where-box');
            var $class = $box.parent('#WhereData');
            $class = $class[0].className;
            $tag = $box.find(self.$WC2);

            self.updateW({0:$class,1:$(e.target).val()}, function (err, data) {
                if (err) {
                    $box.addClass('error');
                } else {
                    $box.removeClass('error');
                    $tag.html(data);
                }
            });
        };
//при изменении and/or, делает блок условий уникальным
        self.choice = function (e) {
            var $tag = $(e.target);

            var $box = $tag.parent('#choice_operator');
            var $class = $box.parent('.where-box').parent('#WhereData');
            var rand = Math.floor(Math.random() * (9999999 - 2 + 1)) + 2;
            $box = $box.children('.operator_a_o');

            if ( $box[0].name == "operator_a_o") {
                for (var i = 0; i < $box.length; i++) {
                    $box[i].name = $box[i].name + '_' + rand;
                }
            }
        };
//получает данные для choice
        self.updateW = function (data, callback) {
            $.ajax({
                       url: '?r=OutputTable/GetDataCondition',
                       type: 'POST',
                       dataType: "json",
                       data: data
                   }).done(function (data) {
                callback(null, data);
            }).fail(function (jqXHR) {
                callback(jqXHR.responseJSON.errors);
            });
        };
//
        self.create = function (data, callback) {
            $.ajax({
                       url: '?r=OutputTable/Create',
                       type: 'POST',
                       dataType: "json",
                       data: data
                   }).done(function (data) {
                callback(null, data);
            }).fail(function (jqXHR) {
                callback(jqXHR.responseJSON.errors);
            });

        };

        self.update = function (id, data, callback) {
            $.ajax({
                       url: '?r=OutputTable/Update&id='+id,
                       type: 'PUT',
                       dataType: "json",
                       data: data
                   }).done(function (data) {
                callback(null, data);
            }).fail(function (jqXHR) {
                callback(jqXHR.responseJSON.errors);
            });

        };

        self.unset = function (data) {
            $.ajax({
                       url: '?r=OutputTable/UnsetExp&class='+data,
                       type: 'POST',
                       dataType: "json",
                       data: ''
                   });
        };

        self.showSuccessBox = function () {
            var interval =  setInterval(function() {
                if (!self.$sucessBox.is(':visible')) {
                    self.$sucessBox.show();
                    hideBox();
                    clearInterval(interval);
                }
            }, 1000);

            function hideBox() {
                setTimeout(function () {
                    self.$sucessBox.hide();
                }, 2000);
            }
        };

        self.blink = function ($box) {

            $('html, body').animate({}, 1000, function () {
                $box.fadeOut()
                    .fadeIn()
                    .fadeOut()
                    .fadeIn()
                    .fadeOut()
                    .fadeIn();
            });
        }
    }

    $(function () {
        if ($('#output-container').length) {
            new OutputTable().init();
        }
    });
})(jQuery, underscore);

(function($) {
    // ***********************************************
    //The main fixedTable function
    $.fn.fixedTable = function(opts) {
        //default options defined in $.fn.fixedTable.defaults - at the bottom of this file.
        var options = $.extend({}, $.fn.fixedTable.defaults, opts);
        var mainid = "#" + options.outerId;
        var tbl = this;
        var layout = buildLayout(tbl, opts);

        var dfe = $(".fixedContainer > ." + options.classHeader + " > table", layout);
        //see the buildLayout() function below

        //we need to set the width (in pixels) for each of the tables in the fixedContainer area
        //but, we need to subtract the width of the fixedColumn area.
        var w = options.width - options.fixedColumnWidth;
        //sanity check
        if (w <= 0) { w = options.width; }

        $(".fixedContainer", layout).width(w);

        $(".fixedContainer ." + options.classHeader, layout).css({
                                                                     width: (w) + "px",
                                                                     "float": "left",
                                                                     "overflow": "hidden"
                                                                 });

        $(".fixedContainer .fixedTable", layout).css({
                                                         "float": "left",
                                                         width: (w + 16) + "px",
                                                         "overflow": "auto"
                                                     });
        $(".fixedContainer", layout).css({
                                             width: w - 1,
                                             "float": "left"
                                         });    //adjust the main container to be just larger than the fixedTable

        $(".fixedContainer ." + options.classFooter, layout).css({
                                                                     width: (w) + "px",
                                                                     "float": "left",
                                                                     "overflow": "hidden"
                                                                 });
        $("." + options.classColumn + " > .fixedTable", layout).css({
                                                                        "width": options.fixedColumnWidth,
                                                                        "overflow": "hidden",
                                                                        "border-collapse": $(tbl).css("border-collapse"),
                                                                        "padding": "0"
                                                                    });

        $("." + options.classColumn, layout).width(options.fixedColumnWidth);
        $("." + options.classColumn, layout).height(options.height);
        $("." + options.classColumn + " ." + options.classHeader + " table tbody tr td", layout).width(options.fixedColumnWidth);
//        $("." + options.classColumn + " ." + options.classHeader + " table tbody tr td", layout).height(dfe.height());
        $("." + options.classColumn + " ." + options.classFooter + " table tbody tr td", layout).width(options.fixedColumnWidth);

        //adjust the table widths in the fixedContainer area
        var fh = $(".fixedContainer > ." + options.classHeader + " > table", layout);
        var ft = $(".fixedContainer > .fixedTable > table", layout);
        var ff = $(".fixedContainer > ." + options.classFooter + " > table", layout);

        var maxWidth = fh.width();
        if (ft.length > 0 && ft.width() > maxWidth) { maxWidth = ft.width(); }
        if (ff.length > 0 && ff.width() > maxWidth) { maxWidth = ff.width(); }


        if (fh.length) { fh.width(maxWidth); }
        if (ft.length) { ft.width(maxWidth); }
        if (ff.length) { ff.width(maxWidth); }

        //adjust the widths of the fixedColumn header/footer to match the fixed columns themselves
        $("." + options.classColumn + " > ." + options.classHeader + " > table > tbody > tr:first > td", layout).each(function(pos) {
            var tblCell = $("." + options.classColumn + " > .fixedTable > table > tbody > tr:first > td:eq(" + pos + ")", layout);
            var tblFoot = $("." + options.classColumn + " > ." + options.classFooter + " > table > tbody > tr:first > td:eq(" + pos + ")", layout);
            var maxWidth = $(this).width();
            if (tblCell.width() > maxWidth) { maxWidth = tblCell.width(); }
            if (tblFoot.length && tblFoot.width() > maxWidth) { maxWidth = tblFoot.width(); }
            $(this).width(maxWidth);
            $(this).height(fh.height());
            $(tblCell).width(maxWidth);
            if (tblFoot.length) { $(tblFoot).width(maxWidth); }
        });


        //set the height of the table area, minus the heights of the header/footer.
        // note: we need to do this after the other adjustments, otherwise these changes would be overwrote
        var h = options.height - parseInt($(".fixedContainer > ." + options.classHeader, layout).height()) - parseInt($(".fixedContainer > ." + options.classFooter, layout).height());
        //sanity check
        if (h < 0) { h = options.height; }
        $(".fixedContainer > .fixedTable", layout).height(h);
        $("." + options.classColumn + " > .fixedTable", layout).height(h);

        //Adjust the fixed column area if we have a horizontal scrollbar on the main table
        // - specifically, make sure our fixedTable area matches the main table area minus the scrollbar height,
        //   and the fixed column footer area lines up with the main footer area (shift down by the scrollbar height)
        var h = $(".fixedContainer > .fixedTable", layout)[0].offsetHeight - 16;
        $("." + options.classColumn + " > .fixedTable", layout).height(h);  //make sure the row area of the fixed
                                                                            // column matches the height of the main
                                                                            // table, with the scrollbar

        // Apply the scroll handlers
        $(".fixedContainer > .fixedTable", layout).scroll(function() { handleScroll(mainid, options); });
        //the handleScroll() method is defined near the bottom of this file.

        //$.fn.fixedTable.adjustSizes(mainid);
        adjustSizes(options);
        $(".fixedHead").height(fh.height());
        return tbl;
    }

    function buildLayout(src, options) {
        //create a working area and add it just after our table.
        //The table will be moved into this working area
        var area = $("<div class=\"fixedArea\"></div>").appendTo($(src).parent());

        //fixed column items
        var fc = $("<div class=\"" + options.classColumn + "\" style=\"float: left;\"></div>").appendTo(area);
        var fch = $("<div class=\"" + options.classHeader + "\"></div>").appendTo(fc);
        var fct = $("<div class=\"fixedTable\"></div>").appendTo(fc);
        var fcf = $("<div class=\"" + options.classFooter + "\"></div>").appendTo(fc);

        //fixed container items
        var fcn = $("<div class=\"fixedContainer\"></div>").appendTo(area);
        var fcnh = $("<div class=\"" + options.classHeader + "\"></div>").appendTo(fcn);
        var fcnt = $("<div class=\"fixedTable\"></div>").appendTo(fcn);
        var fcnf = $("<div class=\"" + options.classFooter + "\"></div>").appendTo(fcn);

        //create the fixed column area
        if (options.fixedColumns > 0 && !isNaN(options.fixedColumns)) {
            buildFixedColumns(src, "thead", options.fixedColumns, fch);
            buildFixedColumns(src, "tbody", options.fixedColumns, fct);
            buildFixedColumns(src, "tfoot", options.fixedColumns, fcf);
            //see the buildFixedColumns() function below
        }

        //Build header / footer areas
        buildFixedTable(src, "thead", fcnh);
        buildFixedTable(src, "tfoot", fcnf);
        //see the buildFixedTable() function below

        //Build the main table
        //we'll cheat here - the src table should only be a tbody section, with the remaining columns,
        //so we'll just add it to the fixedContainer table area.
        fcnt.append(src);
        return area;
    }

    /* ******************************************************************** */
    // duplicate a table section (thead, tfoot, tbody), but only for the desired number of columns
    function buildFixedColumns(src, section, cols, target) {
        //TFOOT - get the needed columns from the table footer
        if ($(section, src).length) {
            var colHead = $("<table></table>").appendTo(target);

            //If we have a thead or tfoot, we're looking for "TH" elements, otherwise we're looking for "TD" elements
            var cellType = "td";  //deafault cell type
            if (section.toLowerCase() == "thead" || section.toLowerCase() == "tfoot") { cellType = "th"; }

            //check each of the rows in the thead
            $(section + " tr", src).each(function() {
                var tr = $("<tr></tr>").appendTo(colHead);
                $(cellType + ":lt(" + cols + ")", this).each(function() {
                    $("<td>" + $(this).html() + "</td>").addClass(this.className).attr("id", this.id).appendTo(tr);
                    //Note, we copy the class names and ID from the original table cells in case there is any
                    // processing on them. However, if the class does anything with regards to the cell size or
                    // position, it could mess up the layout.

                    //Remove the item from our src table.
                    $(this).remove();
                });
            });
        }
    }

    /* ******************************************************************** */
    // duplicate a table section (thead, tfoot, tbody)
    function buildFixedTable(src, section, target) {
        if ($(section, src).length) {
            var th = $("<table></table>").appendTo(target);
            var tr = null;

            //If we have a thead or tfoot, we're looking for "TH" elements, otherwise we're looking for "TD" elements
            var cellType = "td";  //deafault cell type
            if (section.toLowerCase() == "thead" || section.toLowerCase() == "tfoot") { cellType = "th"; }

            $(section + " tr", src).each(function() {
                var tr = $("<tr></tr>").appendTo(th);
                $(cellType, this).each(function() {
                    $("<td>" + $(this).html() + "</td>").appendTo(tr);
                });

            });
            //The header *should* be added to our head area now, so we can remove the table header
            $(section, src).remove();
        }
    }

    // ***********************************************
    // Handle the scroll events
    function handleScroll(mainid, options) {
        //Find the scrolling offsets
        var tblarea = $(mainid + " .fixedContainer > .fixedTable");
        var x = tblarea[0].scrollLeft;
        var y = tblarea[0].scrollTop;

        $(mainid + " ." + options.classColumn + " > .fixedTable")[0].scrollTop = y;
        $(mainid + " .fixedContainer > ." + options.classHeader)[0].scrollLeft = x;
        $(mainid + " .fixedContainer > ." + options.classFooter)[0].scrollLeft = x;
    }

    // ***********************************************
    //  Reset the heights of the rows in our fixedColumn area
    function adjustSizes(options) {

        var Id = options.outerId;
        var maintbheight = options.height;
        var backcolor = options.Contentbackcolor;
        var hovercolor = options.Contenthovercolor;
        var fixedColumnbackcolor = options.fixedColumnbackcolor;
        var fixedColumnhovercolor = options.fixedColumnhovercolor;

        // row height
        $("#" + Id + " ." + options.classColumn + " .fixedTable table tbody tr").each(function(i) {
            var maxh = 0;
            var fixedh = $(this).height();
            var contenth = $("#" + Id + " .fixedContainer .fixedTable table tbody tr").eq(i).height();
            if (contenth > fixedh) {
                maxh = contenth;
            }
            else {
                maxh = fixedh;
            }
            //$(this).height(contenth);
            $(this).children("td").height(maxh);
            $("#" + Id + " .fixedContainer .fixedTable table tbody tr").eq(i).children("td").height(maxh);
        });

        //adjust the cell widths so the header/footer and table cells line up
        var htbale = $("#" + Id + " .fixedContainer ." + options.classHeader + " table");
        var ttable = $("#" + Id + " .fixedContainer .fixedTable table");
        var ccount = $("#" + Id + " .fixedContainer ." + options.classHeader + " table tr:first td").size();
        var widthArray = new Array();
        var totall = 0;
        var i = 0;

        $("#" + Id + " .fixedContainer ." + options.classHeader + " table tr:first td").each(function(pos) {
            var cwidth = $(this).width();
            $("#" + Id + " .fixedContainer .fixedTable table tbody td").each(function(i) {
                if (i % ccount == pos) {
                    if ($(this).width() > cwidth) {
                        cwidth = $(this).width();
                    }
                }
            });
            widthArray[pos] = cwidth+4;
            totall += (cwidth + 2);
        });

        $("#" + Id + " .fixedContainer ." + options.classHeader + " table").width(totall + 1000);
        $("#" + Id + " .fixedContainer .fixedTable table").width(totall + 1000);
        $("#" + Id + " .fixedContainer ." + options.classFooter + " table").width(totall + 1000);
        for (i = 0; i < widthArray.length; i++) {
            $("#" + Id + " .fixedContainer ." + options.classHeader + " table tr td").each(function(j) {
                if (j % ccount == i) {
                    $(this).css("width", widthArray[i] + "px");
                }
            });

            $("#" + Id + " .fixedContainer .fixedTable table tr td").each(function(j) {
                if (j % ccount == i) {
                    $(this).css("width", widthArray[i] + "px");
                }
            });

            $("#" + Id + " .fixedContainer ." + options.classFooter + " table tr td").each(function(j) {
                if (j % ccount == i) {
                    $(this).css("width", widthArray[i] + "px");
                }
            });
        }

        // mouse in/out fixedColumn's fixedtable, change background color.
        $("#" + Id + " ." + options.classColumn + " .fixedTable table tr").each(function(i) {
            $(this).mouseover(function() {
                $(this).children("td").css({
                                               "background-color": fixedColumnhovercolor
                                           });
                var obj = $("#" + Id + " .fixedContainer .fixedTable table tr").eq(i);
                obj.children("td").css({
                                           "background-color": hovercolor
                                       });
                obj.children("td").children("pre").css({
                                                           "background-color": hovercolor
                                                       });
            });
            $(this).mouseout(function() {
                $(this).children("td").css({
                                               "background-color": fixedColumnbackcolor
                                           });
                var obj = $("#" + Id + " .fixedContainer .fixedTable table tr").eq(i);
                obj.children("td").css({
                                           "background-color": backcolor
                                       });
                obj.children("td").children("pre").css({
                                                           "background-color": backcolor
                                                       });
            });
        });

        // mouse in/out fixedContainer's fixedtable, change background color.
        $("#" + Id + " .fixedContainer .fixedTable table tr").each(function(i) {
            $(this).mouseover(function() {
                $(this).children("td").css({
                                               "background-color": hovercolor
                                           });
                $(this).children("td").children("pre").css({
                                                               "background-color": hovercolor
                                                           });
                var obj = $("#" + Id + " ." + options.classColumn + " .fixedTable table tr").eq(i);
                obj.children("td").css({
                                           "background-color": fixedColumnhovercolor
                                       });

            });
            $(this).mouseout(function() {
                $(this).children("td").css({
                                               "background-color": backcolor
                                           });
                $(this).children("td").children("pre").css({
                                                               "background-color": backcolor
                                                           });
                var obj = $("#" + Id + " ." + options.classColumn + " .fixedTable table tr").eq(i);
                obj.children("td").css({
                                           "background-color": fixedColumnbackcolor
                                       });
            });
        });

        var contenttbH = $("#" + Id + " .fixedContainer .fixedTable table").height();
        if (contenttbH < maintbheight) {
            $("#" + Id + " ." + options.classColumn + " .fixedTable").height(contenttbH + 20);
            $("#" + Id + " .fixedContainer .fixedTable").height(contenttbH + 20);

            $("#" + Id + " .fixedContainer ." + options.classHeader).width($("#" + Id + " .fixedContainer ." + options.classHeader).width() + 16);
            $("#" + Id + " .fixedContainer ." + options.classFooter).width($("#" + Id + " .fixedContainer ." + options.classHeader).width());
        }
        else {
            //offset the footer by the height of the scrollbar so that it lines up right.
            $("#" + Id + " ." + options.classColumn + " > ." + options.classFooter).css({
                                                                                            "position": "relative",
                                                                                            "top": 16
                                                                                        });
        }
    }

})(jQuery);