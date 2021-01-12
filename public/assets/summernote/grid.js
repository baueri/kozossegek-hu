! function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof module && module.exports ? module.exports = e(require("jquery")) : e(window.jQuery)
}(function (c) {
    var l = {
        wrapper: "row",
        columns: ["col-md-12", "col-md-6", "col-md-4", "col-md-3"]
    };
    c.extend(!0, c.summernote, {
        plugins: {
            gridPlugin: function (n) {
                var i = n.options.langInfo,
                    o = n.options.callbacks,
                    e = n.options.icons,
                    t = c.summernote.ui,
                    d = c.extend(l, n.options.grid || {}),
                    r = this;
                n.memo("button.grid", function () {
                    return t.buttonGroup([t.button({
                        className: "dropdown-toggle",
                        contents: '<i class="' + e.grid + '"/>',
                        tooltip: i.grid.tooltip,
                        data: {
                            toggle: "dropdown"
                        }
                    }), t.dropdown({
                        className: "dropdown-menu dropdown-style text-grey-800 bg-white",
                        contents: r.createDropdownContent(),
                        callback: r.createGrid
                    })]).render()
                }), this.createDropdownContent = function () {
                    for (var e = "", t = 0; t < d.columns.length; t++) {
                        null != d.columns[t] && (e += this.createDropdownElement(t));
                    }
                    return e
                }, this.createDropdownElement = function (e) {
                    var t = document.createElement("li"),
                        n = document.createElement("a");
                    return n.setAttribute("class", "text-grey-800"), n.setAttribute("href", "#"), n.setAttribute("data-index", e), n.innerText = i.grid.label + " " + (e + 1), t.appendChild(n), t.outerHTML
                }, this.createGrid = function (e) {
                    e.find("li a").each(function () {
                        c(this).click(function () {
                            var e = c(this).data("index"),
                                t = r.createGridNode(e);
                            return o.onGridInsert ? n.triggerEvent("grid.insert", t) : n.invoke("editor.insertNode", t), !1
                        })
                    })
                }, this.createGridNode = function (e) {
                    var t = document.createElement("div");
                    t.className = d.wrapper;
                    for (var n = 0; n <= e; n++) {
                        var o = document.createElement("div"),
                            r = document.createElement("p");
                        o.className = d.columns[e], r.innerHTML = i.grid.label + " #" + (n + 1), o.appendChild(r), t.appendChild(o)
                    }
                    return t
                }
            }
        },
        options: {
            grid: l,
            callbacks: {
                onGridInsert: null
            },
            icons: {
                grid: "fa fa-th"
            }
        },
        lang: {
            "en-US": {
                grid: {
                    tooltip: "Cellák",
                    label: "Cella "
                }
            }
        }
    })
});
