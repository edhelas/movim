var Drawer = {
    open: function (key) {
        document.querySelector('#drawer').dataset.type = key;
        MovimTpl.pushAnchorState(key, function () { Drawer.clear(key) });
    },
    filled : function() {
        return (document.querySelector('#drawer').innerHTML != '');
    },
    hasTabs : function () {
        return Drawer.filled()
            && document.querySelector('#drawer').contains(document.querySelector('#navtabs'));
    },
    clear : function(key) {
        if (key && document.querySelector('#drawer').dataset.type == key) {
            Drawer_ajaxClear();
            return;
        }

        if (key == undefined) {
            MovimTpl.clearAnchorState();
            Drawer_ajaxClear();
        }
    },
    close : function() {
        if (Drawer.filled()) {
            history.back();
        }
    }
}

movimAddOnload(function() {
    document.addEventListener('keydown', function(e) {
        if (Drawer.filled()
        && e.key == 'Escape') {
            history.back();
        }
    }, false);
});

MovimWebsocket.initiate(() => document.querySelector('#drawer').innerHTML = '');
