$(function () {

    function setDisplay() {
        if (window.IsWidescreen) {
            $("#mnuWideScreen").text("Set Widescreen Off").closest("li").addClass("active");
            $("#NavigationMain > div.container").removeClass("container").addClass("container-fluid");
            $("#MainContent").removeClass("container").addClass("container-fluid");

            var container = $("#NavigationToolbar > div.container");
            container.removeClass("container toolbar-padding").addClass("container-fluid toolbar-nopadding");
            container.find("div.btn-group:first").removeClass("widescreen-off").addClass("widescreen-on");
        } else {
            $("#mnuWideScreen").text("Set Widescreen On").closest("li").removeClass("active");
            $("#NavigationMain > div.container-fluid").removeClass("container-fluid").addClass("container");
            $("#MainContent").removeClass("container-fluid").addClass("container");

            var container = $("#NavigationToolbar > div.container-fluid");
            container.removeClass("container-fluid toolbar-nopadding").addClass("container toolbar-padding");
            container.find("div.btn-group:first").removeClass("widescreen-on").addClass("widescreen-off");
        }

        window.IsWidescreen = !window.IsWidescreen;
    }

    function setContentTab() {
        $("#NavigationToolbar div.btn-group a").removeClass("active");
        $("#btn" + window.ContentTab).addClass("active");
        $("#ContentTabs div.tab-pane").removeClass("active");
        $("#ContentTabs-" + window.ContentTab).addClass("active");
    }

    function loadContent(showLoadingToast) {
        showLoadingToast = typeof showLoadingToast === "boolean" ? showLoadingToast : true;
        var isError = false;
        $.ajax({
            url: "/content",
            type: "GET",
            beforeSend: function () {
                $("#MainContent").empty();
                if (showLoadingToast) {
                    window.showToast("Page Content Loading");
                }
            },
            complete: function () {
                setContentTab();
                if (isError === false) {
                    window.hideToast();
                }
            },
            error: function () {
                isError = true;
                window.showToast("Error Loading Page Content", true, true);
            },
            success: function (result) {
                $("#MainContent").html(result);
            }
        });
    }

    function clearOpcache() {
        var error = "Opcache Reset Failed";

        $.ajax({
            url: "/clear-cache",
            type: "POST",
            dataType: 'json',
            data: {
                'reset': true
            },
            beforeSend: function () {
                $("#btnClearCache").attr("disabled", true);
            },
            complete: function () {
                $("#btnClearCache").attr("disabled", false);
            },
            error: function () {
                window.showToast(error, true, true);
            },
            success: function (response) {
                if (response.status === "success") {
                    window.showToast("Opcache Successfully Reset :: Page Content Loading");
                    loadContent(false);
                } else {
                    window.showToast(error, true, true);
                }
            }
        });
    }

    function setAutoRefresh() {
        if (window.RefreshTimerOn === true) {
            $("#mnuAutoRefresh").text("Disable Auto Refresh").closest("li").addClass("active");
            RefreshTimer = setInterval(function () {
                loadContent();
            }, window.RefreshInterval * 1000);
        } else {
            $("#mnuAutoRefresh").text("Enable Auto Refresh").closest("li").removeClass("active");
            if (typeof RefreshTimer !== "undefined") {
                clearTimeout(RefreshTimer);
            }
        }

        window.RefreshTimerOn = !window.RefreshTimerOn;
    }

    $("#mnuAutoRefresh").click(function (event) {
        event.preventDefault();
        setAutoRefresh();
    });

    $("#mnuWideScreen").click(function (event) {
        event.preventDefault();
        setDisplay();
    });

    $("#NavigationToolbar a.btn").click(function (event) {
        event.preventDefault();
        window.ContentTab = $(this).data('tab');
        $(this).blur();
        setContentTab();
    });

    $("#ConfirmationModalYes").click(function (event) {
        event.preventDefault();
        var mode = $("#ConfirmationModal").data("mode");
        var id = $("#ConfirmationModal").data("id");
        switch (mode) {
            case "clearCache":
                $("#ConfirmationModal").modal("hide");
                clearOpcache();
                break;
            default:
                $("#ConfirmationModal").modal("hide");
                showErrorToast("<span>Invalid Application Mode</span>");
        }
    });

    $("#btnClearCache").click(function (event) {
        event.preventDefault();
        if (window.OpcacheResetConfirm === true) {
            $("#ConfirmationModalTitle").text("Reset Cache");
            $("#ConfirmationModalBody").html("<p>Clear all cached entries?</p>");
            $("#ConfirmationModal").data("mode", "clearCache").modal("show");
        } else {
            clearOpcache();
        }
    });

    window.showToast = function (text, fade, isError) {
        if (window.ToastDisplayed) {
            $("#ToastBanner").stop().hide();
            window.ToastDisplayed = false;
        }

        text = typeof text !== "undefined" ? text : "";
        fade = typeof fade === "boolean" ? fade : false;
        isError = typeof isError === "boolean" ? isError : false;

        var backgroundColour = (isError === true) ? "red" : "#383838";

        $("#ToastBanner p.content").css("background-color", backgroundColour).text(text);
        window.ToastDisplayed = true;
        if (fade) {
            $("#ToastBanner").stop().delay(1600).fadeOut(400, function () {
                window.ToastDisplayed = false;
            }).show();
        } else {
            $("#ToastBanner").stop().show();
        }
    };

    window.hideToast = function () {
        $("#ToastBanner").stop().delay(1600).fadeOut(400, function () {
            window.ToastDisplayed = false;
        });
    };

    /*
     * The following Globals are configured in the application.global.php file
     * The are initialised in the index layout template
     * 
     * window.ContentTab
     * window.IsWidescreen
     * window.OpcacheResetConfirm
     * window.RefreshTimerOn
     * window.RefreshInterval
     * window.ShowScriptsDetailsColumn     
     */
    window.ContentTab = ($.inArray(window.ContentTab, ["Overview", "Scripts", "Information"]) !== -1) ? window.ContentTab : "Overview";
    window.IsWidescreen = typeof !!window.IsWidescreen === "boolean" ? !!window.IsWidescreen : false;
    window.OpcacheResetConfirm = typeof !!window.OpcacheResetConfirm === "boolean" ? !!window.OpcacheResetConfirm : false;
    window.RefreshTimerAutoStart = typeof !!window.RefreshTimerAutoStart === "boolean" ? !!window.RefreshTimerAutoStart : false;
    window.RefreshTimerOn = (window.RefreshTimerAutoStart) ? false : true;
    window.RefreshInterval = parseInt(window.RefreshInterval) || 600;
    window.ShowScriptsDetailsColumn = typeof !!window.ShowScriptsDetailsColumn === "boolean" ? !!window.ShowScriptsDetailsColumn : false;
    window.ToastDisplayed = false;
    window.CachedFilesTotal = 0;

    setDisplay();
    loadContent();
    setContentTab();
    if (window.RefreshTimerAutoStart) {
        $("#mnuAutoRefresh").trigger("click");
    }
});
