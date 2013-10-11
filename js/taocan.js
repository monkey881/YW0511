/* $Id: listtable.js 14980 2008-10-22 05:01:19Z testyang $ */

if (typeof Utils != 'object') {
    alert('Utils object doesn\'t exists.');
}

var listTable = new Object;

listTable.query = "query";
listTable.filter = new Object;
listTable.url = location.href.lastIndexOf("?") == -1 ? location.href.substring((location.href.lastIndexOf("/")) + 1) : location.href.substring((location.href.lastIndexOf("/")) + 1, location.href.lastIndexOf("?"));
listTable.url += "?is_ajax=1";

/**
 * 创建一个可编辑区
 */
listTable.edit = function (obj, act, id) {
    var tag = obj.firstChild.tagName;

    if (typeof (tag) != "undefined" && tag.toLowerCase() == "input") {
        return;
    }

    /* 保存原始的内容 */
    var org = obj.innerHTML;
    var val = Browser.isIE ? obj.innerText : obj.textContent;

    /* 创建一个输入框 */
    var txt = document.createElement("INPUT");
    txt.value = (val == 'N/A') ? '' : val;
    txt.style.width = (obj.offsetWidth + 0) + "px";

    /* 隐藏对象中的内容，并将输入框加入到对象中 */
    obj.innerHTML = "";
    obj.appendChild(txt);
    txt.focus();

    /* 编辑区输入事件处理函数 */
    txt.onkeypress = function (e) {
        var evt = Utils.fixEvent(e);
        var obj = Utils.srcElement(e);

        if (evt.keyCode == 13) {
            obj.blur();

            return false;
        }

        if (evt.keyCode == 27) {
            obj.parentNode.innerHTML = org;
        }
    }

    /* 编辑区失去焦点的处理函数 */
    txt.onblur = function (e) {
        if (Utils.trim(txt.value).length > 0) {
            res = $.ajax({
                url: listTable.url,
                data: { act: act, val: Utils.trim(txt.value), id: id },
                type: 'POST',
                dataType: 'json',
                async: false
            });
            if (res.message) {
                alert(res.message);
            }

            try {
                document.getElementById('mycl').innerHTML = result.content;

                getlist(0);

                if (typeof result.filter == "object") {
                    listTable.filter = result.filter;
                }

            }
            catch (e) {
                alert(e.message);
            }
        }
        else {
            obj.innerHTML = org;
        }
    }
}

/**
 * 切换状态
 */
listTable.toggle = function (obj, act, id) {
    var val = (obj.src.match(/yes.gif/i)) ? 0 : 1;
    res = $.ajax({
        url: this.url,
        data: { act: act, val: val, id: id },
        type: 'POST',
        dataType: 'json',
        async: false
    });
    if (res.message) {
        alert(res.message);
    }

    if (res.error == 0) {
        obj.src = (res.content > 0) ? 'images/yes.gif' : 'images/no.gif';
    }
}

/**
 * 切换排序方式
 */
listTable.sort = function (sort_by, sort_order) {
    var args = { act: +this.query, sort_by: sort_by, sort_order: '' };
    if (this.filter.sort_by == sort_by) {
        args.sort_order = this.filter.sort_order == "DESC" ? "ASC" : "DESC";
    } else {
        args.sort_order = "DESC";
    }
    for (var i in this.filter) {
        if (typeof (this.filter[i]) != "function" &&
          i != "sort_order" && i != "sort_by" && !Utils.isEmpty(this.filter[i])) {
            args[i] = this.filter[i];
        }
    }
    this.filter['page_size'] = this.getPageSize();
    $.ajax({
        url: this.url,
        data: args,
        type: 'POST',
        dataType: 'json',
        success: this.listCallback
    });
}

/**
 * 翻页
 */
listTable.gotoPage = function (page) {
    if (page != null) this.filter['page'] = page;

    if (this.filter['page'] > this.pageCount) this.filter['page'] = 1;

    this.filter['page_size'] = this.getPageSize();

    this.loadList();
}

/**
 * 载入列表
 */
listTable.loadList = function () {
    var args = { act: this.query };
    this.compileFilter(args);
    $.ajax({
        url: this.url,
        data: args,
        type: 'POST',
        dataType: 'json',
        success: this.listCallback
    });
}

listTable.addshop = function () {
    var args = { act: this.query };
    this.compileFilter(args);
    $.ajax({
        url: this.url,
        data: args,
        type: 'POST',
        dataType: 'json',
        success: this.listmycailan
    });
}
/**
 * 删除列表中的一个记录
 */
listTable.remove = function (id, cfm, opt) {
    if (opt == null) {
        opt = "remove";
    }

    if (confirm(cfm)) {
        $.ajax({
            url: this.url,
            data: { act: opt + "", id: id },
            type: 'GET',
            dataType: 'json',
            success: this.listCallback
        });
    }
}

listTable.delshop = function (id) {
    var args = { act: 'delshop', id: id };
    this.compileFilter(args)
    $.ajax({
        url: this.url,
        data: args,
        type: 'GET',
        dataType: 'json',
        success: this.listmycailan
    });

}

listTable.gotoPageFirst = function () {
    if (this.filter.page > 1) {
        listTable.gotoPage(1);
    }
}

listTable.gotoPagePrev = function () {
    if (this.filter.page > 1) {
        listTable.gotoPage(this.filter.page - 1);
    }
}

listTable.gotoPageNext = function () {
    if (this.filter.page < listTable.pageCount) {
        listTable.gotoPage(parseInt(this.filter.page) + 1);
    }
}

listTable.gotoPageLast = function () {
    if (this.filter.page < listTable.pageCount) {
        listTable.gotoPage(listTable.pageCount);
    }
}

listTable.changePageSize = function (e) {
    var evt = Utils.fixEvent(e);
    if (evt.keyCode == 13) {
        listTable.gotoPage();
        return false;
    };
}
listTable.changeAddress = function () {
    var args = { act: this.query };
    this.compileFilter(args)
    $.ajax({
        url: this.url,
        data: args,
        type: 'POST',
        dataType: 'json'
    });
}
listTable.listCallback = function (result, txt) {
    if (result.error > 0) {
        alert(result.message);
    }
    else {
        try {
            document.getElementById('listdiv').innerHTML = result.content;

            if (typeof result.filter == "object") {
                listTable.filter = result.filter;
            }

            listTable.pageCount = result.page_count;
            hoverjs();
        }
        catch (e) {
            alert(e.message);
        }
    }
}

listTable.listmycailan = function (result, txt) {
    getlist(0);
    if (result.message) {
        alert(result.message);
    }
    if (result.error > 0) {
        alert(result.message);
    }
    else {
        try {
            document.getElementById('mycl').innerHTML = result.content;

            if (typeof result.filter == "object") {
                listTable.filter = result.filter;
            }

        }
        catch (e) {
            alert(e.message);
        }
    }
}
listTable.selectAll = function (obj, chk) {
    if (chk == null) {
        chk = 'checkboxes';
    }

    var elems = obj.form.getElementsByTagName("INPUT");

    for (var i = 0; i < elems.length; i++) {
        if (elems[i].name == chk || elems[i].name == chk + "[]") {
            elems[i].checked = obj.checked;
        }
    }
}

listTable.compileFilter = function (args) {
    for (var i in this.filter) {
        if (typeof (this.filter[i]) != "function" && typeof (this.filter[i]) != "undefined") {
            args[i] = this.filter[i];
        }
    }
    return args;
}

listTable.getPageSize = function () {
    var ps = 15;

    pageSize = document.getElementById("pageSize");

    if (pageSize) {
        ps = Utils.isInt(pageSize.value) ? pageSize.value : 15;
        document.cookie = "ECSCP[page_size]=" + ps + ";";
    }
}

listTable.addRow = function (checkFunc) {
    cleanWhitespace(document.getElementById("listDiv"));
    var table = document.getElementById("listDiv").childNodes[0];
    var firstRow = table.rows[0];
    var newRow = table.insertRow(-1);
    newRow.align = "center";
    var items = new Object();
    for (var i = 0; i < firstRow.cells.length; i++) {
        var cel = firstRow.cells[i];
        var celName = cel.getAttribute("name");
        var newCel = newRow.insertCell(-1);
        if (!cel.getAttribute("ReadOnly") && cel.getAttribute("Type") == "TextBox") {
            items[celName] = document.createElement("input");
            items[celName].type = "text";
            items[celName].style.width = "50px";
            items[celName].onkeypress = function (e) {
                var evt = Utils.fixEvent(e);
                var obj = Utils.srcElement(e);

                if (evt.keyCode == 13) {
                    listTable.saveFunc();
                }
            }
            newCel.appendChild(items[celName]);
        }
        if (cel.getAttribute("Type") == "Button") {
            var saveBtn = document.createElement("input");
            saveBtn.type = "image";
            saveBtn.src = "./images/icon_add.gif";
            saveBtn.value = save;
            newCel.appendChild(saveBtn);
            this.saveFunc = function () {
                if (checkFunc) {
                    if (!checkFunc(items)) {
                        return false;
                    }
                }
                var args = { act: 'add' };
                for (var key in items) {
                    if (typeof (items[key]) != "function") {
                        args[key] = items[key].value;
                    }
                }
                res = $.ajax({
                    url: listTable.url,
                    data: args,
                    type: 'POST',
                    dataType: 'json',
                    async: false
                });
                if (res.error) {
                    alert(res.message);
                    table.deleteRow(table.rows.length - 1);
                    items = null;
                }
                else {
                    document.getElementById("listDiv").innerHTML = res.content;
                    if (document.getElementById("listDiv").childNodes[0].rows.length < 6) {
                        listTable.addRow(checkFunc);
                    }
                    items = null;
                }
            }
            saveBtn.onclick = this.saveFunc;

            //var delBtn   = document.createElement("input");
            //delBtn.type  = "image";
            //delBtn.src = "./images/no.gif";
            //delBtn.value = cancel;
            //newCel.appendChild(delBtn);
        }
    }

}
function hoverjs() {
    $('#listdiv .select').mouseenter(function () {
        $(this).stop().animate({ "top": "-180px" }, 800);
    })
    $('#listdiv .select').mouseleave(function () {
        $(this).stop().animate({ "top": "0" }, 800);
    })
}