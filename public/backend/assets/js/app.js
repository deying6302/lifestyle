(function ($) {
    "use strict";

    // Function to save to localStorage
    function saveToLocalStorage(key, value) {
        localStorage.setItem(key, value);
    }

    // Function to retrieve from localStorage
    function getFromLocalStorage(key) {
        return localStorage.getItem(key);
    }

    // Function to remove from localStorage
    function removeFromLocalStorage(key) {
        localStorage.removeItem(key);
    }

    //////////////////////// Window On Load //////////////////
    $(window).on("load", function () {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
    });

    ///////////////// Flip Menu ///////////
    $(".flip-menu-toggle").on("click", function () {
        $(".flip-menu").toggleClass("active");
    });
    $(".flip-menu-close").on("click", function () {
        $(".flip-menu").toggleClass("active");
    });

    //////////////////////// Chat ////////////////////
    $(".chat-contact").on("click", function () {
        $(".chat-contact-list").toggleClass("active");
    });
    $(".chat-profile").on("click", function () {
        $(".chat-user-profile").toggleClass("active");
    });
    $(".scrollerchat").slimScroll({
        height: "460px",
        color: "#fff",
    });

    /////////////////////// Loader /////////////////////
    var angle = 0;
    setInterval(function () {
        $(".se-pre-con img")
            .css("-webkit-transform", "rotate(" + angle + "deg)")
            .css("-moz-transform", "rotate(" + angle + "deg)")
            .css("-ms-transform", "rotate(" + angle + "deg)");
        angle++;
        angle++;
        angle++;
    }, 10);

    $(".popupchat").slimScroll({
        height: "220px",
        color: "#fff",
    });

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $(".checkall").on("click", function () {
        $(".mail-app input:checkbox").not(this).prop("checked", this.checked);
    });

    /**************** Menu **********************/
    $(".sidebar-menu .dropdown>a").on("click", function () {
        if ($(this).parent().hasClass("active")) {
            $(this).parent().find(">.sub-menu").slideUp("slow");
            $(this).parent().removeClass("active");
        } else {
            $(this).parent().find(">.sub-menu").slideDown("slow");
            $(this).parent().addClass("active");
        }
        return false;
    });

    /**************** Chat Pop Up **********************/
    $(".chatbutton").on("click", function () {
        $(".chatwindow").toggle();
        return false;
    });

    /*==============================================================
     Sidebar
     ============================================================= */
    $(".sidebarCollapse").on("click", function () {
        $("body").toggleClass("compact-menu");
        $(".sidebar").toggleClass("active");
    });

    $(".mobilesearch").on("click", function () {
        $(".search-form").toggleClass("d-none");
    });

    /////////////////////////// Datepicker ////////////////////////
    if (typeof $.fn.datepicker !== "undefined") {
        $(".datepicker").datepicker();
    }

    /////////////////////////// Wizard Form ////////////////////////

    $(".nexttab").click(function () {
        var nextId = $(this).parents(".tab-pane").next().attr("id");
        $('[href="#' + nextId + '"]').tab("show");
    });

    $(".prevtab").click(function () {
        var nextId = $(this).parents(".tab-pane").prev().attr("id");
        $('[href="#' + nextId + '"]').tab("show");
    });

    /********************************** Image Background *************************/
    $(".background-image-maker").each(function () {
        var imgURL = $(this).next(".holder-image").find("img").attr("src");
        $(this).css("background-image", "url(" + imgURL + ")");
    });

    /********************************** Top Scroll *************************/
    $(".scrollup").on("click", function () {
        $("html, body").animate(
            {
                scrollTop: 0,
            },
            600
        );
        return false;
    });

    /****************************** Window Scroll ****************************/
    $(window).on("scroll", function () {
        /*==============================================================
         Back To Top
         =============================================================*/
        if ($(this).scrollTop() > 100) {
            $(".scrollup").fadeIn();
        } else {
            $(".scrollup").fadeOut();
        }
    });

    /*==============================================================
     Form Validation
     ============================================================= */
    var forms = document.getElementsByClassName("needs-validation");
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener(
            "submit",
            function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            },
            false
        );
    });

    /*==============================================================
     Sidebar Settings
     ============================================================= */

    var settinghtml = `<div id="settings" class="">
            <a href="#" id="settingbutton" class="setting">
                <h5 class="mb-0"><i class="icon-settings"></i></h5>
            </a>
            <div class="sidbarchat p-3">
                <h5 class="mb-0">TÙY CHỈNH MẪU</h5>
                <p>Tùy chỉnh mẫu của bạn</p>
                <hr/>
                <h6>MÀU MẪU</h6>
                <ul class="list-inline float-left claerfix">
                    <li class="color-box m-2 list-inline-item float-left color1" data-color="#1e3d73"></li>
                    <li class="color-box m-2 list-inline-item float-left color2" data-color="#0bb2d4"></li>
                    <li class="color-box m-2 list-inline-item float-left color3" data-color="#17b3a3"></li>
                    <li class="color-box m-2 list-inline-item float-left color4" data-color="#eb6709"></li>
                    <li class="color-box m-2 list-inline-item float-left color5" data-color="#76c335"></li>
                    <li class="color-box m-2 list-inline-item float-left color6" data-color="#3e8ef7"></li>
                    <li class="float-left list-inline-item"><input type="color" class="cursor-pointer color m-2"  value="#1e3d73"></li>
                </ul>

                <hr class="float-left w-100"/>

                <h6>KIỂU MẪU</h6>
                <label class="chkbox">Ánh Sáng
                    <input name="style" value="light" class="style" type="radio" >
                    <span class="checkmark"></span>
                </label> <br/>
                <label class="chkbox mt-2">Tối
                    <input name="style" value="dark" class="style" type="radio" >
                    <span class="checkmark"></span>
                </label> <br/>
                <label class="chkbox mt-2">Nửa Tối
                    <input name="style" value="semi-dark" class="style" type="radio" >
                    <span class="checkmark"></span>
                </label>
                <br />
                <br />
                <hr class="float-left w-100"/>
                <label class="chkbox horizontal mb-2">Menu Ngang
                    <input name="horizontal" value="horizontal-menu" class="horizontallayout" type="checkbox" >
                    <span class="checkmark"></span>
                </label><br/>
                <label class="chkbox compact">Thanh Bên Nhỏ Gọn
                    <input name="compact" value="compact" class="sidebar" type="checkbox" >
                    <span class="checkmark"></span>
                </label>

                <button id="clearLocalStorage" class="btn btn-primary btn-sm" style="margin-top: 200px; width: 100%;">Đặt lại cấu hình</button>
            </div>
        </div>`;

    $("body").append(settinghtml);

    $(".setting").on("click", function () {
        $("#settings").toggleClass("active");
        return false;
    });

    var uri = window.location.href.toString();
    if (uri.indexOf("?") > 0) {
        removeFromLocalStorage("menulayout");
        removeFromLocalStorage("themecolor");
        removeFromLocalStorage("sidebarstyle");
        removeFromLocalStorage("horizontal");
        removeFromLocalStorage("menuicon");
    }

    ////////////////////////////// TEMPLATE Color /////////////////////////
    $(".color-box").on("click", function () {
        $("input.color").val($(this).data("color"));
        $("body").css("--primarycolor", $("input.color").val());
        $(".dark").css("--primarycolor", $("input.color").val());
        $(".semi-dark").css("--primarycolor", $("input.color").val());
        $(".semi-dark-alt").css("--primarycolor", $("input.color").val());
        saveToLocalStorage("cookiesprimarycolor", $("input.color").val());
    });
    $("input.color").on("change", function () {
        $("body").css("--primarycolor", $("input.color").val());
        $(".dark").css("--primarycolor", $("input.color").val());
        $(".semi-dark").css("--primarycolor", $("input.color").val());
        $(".semi-dark-alt").css("--primarycolor", $("input.color").val());
        saveToLocalStorage("cookiesprimarycolor", $(this).val());
    });

    var cookiesprimarycolor = getFromLocalStorage("cookiesprimarycolor");
    if (cookiesprimarycolor != null && cookiesprimarycolor != "") {
        $("input.color").val(cookiesprimarycolor);
        $("body").css("--primarycolor", cookiesprimarycolor);
        $(".dark").css("--primarycolor", cookiesprimarycolor);
        $(".semi-dark").css("--primarycolor", cookiesprimarycolor);
        $(".semi-dark-alt").css("--primarycolor", cookiesprimarycolor);
    }

    ///////////////////////////////// Sidebar Color //////////////////////////////
    $("input.sidebarcolor").on("change", function () {
        $(".sidebar").css("background", $("input.sidebarcolor").val());
        saveToLocalStorage("cookiessidebarcolor", $("input.sidebarcolor").val());
    });
    var cookiessidebarcolor = getUrlParameter("cookiessidebarcolor");
    if (cookiessidebarcolor != null && cookiessidebarcolor != "") {
        saveToLocalStorage("cookiessidebarcolor", cookiessidebarcolor);
    }
    var themecolor = getFromLocalStorage("themecolor");
    if (themecolor == "semi-dark") {
        var cookiessidebarcolor = getFromLocalStorage("cookiessidebarcolor");
        if (cookiessidebarcolor != null && cookiessidebarcolor != "") {
            $("input.sidebarcolor").val(cookiessidebarcolor);
            $(".sidebar").css("background", cookiessidebarcolor);
            $("#header-fix .logo-bar").css("background", cookiessidebarcolor);
        }
    }

    //////////////////////////// TEMPLATE Style //////////////////////////////////

    $(".style").on("click", function () {
        $("body").removeClass(
            "light dark semi-dark dark-alt semi-dark-alt gradient"
        );
        $(
            "body, #header-fix, #header-fix .logo-bar, .sidebar .dropdown-menu, #settings .sidbarchat,.gradient .sidebar, .horizontal-menu #header-fix, .horizontal-menu .sidebar .sidebar-menu > li.active, .horizontal-menu .sidebar .sidebar-menu > li:hover, .horizontal-menu .sidebar .sidebar-menu > li ul, .compact-menu .sidebar, .gradient .dropdown-menu"
        ).removeAttr("style");
        $("body").addClass($(this).val());
        $("html").css("--primarycolor", $("input.color").val());
        $(".dark").css("--primarycolor", $("input.color").val());
        $(".semi-dark").css("--primarycolor", $("input.color").val());
        if ($(this).val() == "semi-dark") {
            $(".sidebar").css("background", $("input.sidebarcolor").val());
            $("#header-fix .logo-bar").css(
                "background",
                $("input.sidebarcolor").val()
            );
            $(".sidecolor").show();
        } else {
            $(".sidebar").css("background", "");
            $("#header-fix .logo-bar").css("background", "");
            $(".sidecolor").hide();
        }
        if ($(this).val() == "gradient") {
            $(".gradient-img").show();
        } else {
            removeFromLocalStorage("cookiesprimarycolor");
            saveToLocalStorage("cookiesprimarycolor", "#1e3d73");
            $("html").css("--primarycolor", "#1e3d73");
            $(".gradient-img").hide();
        }
        if ($(this).val() == "dark") {
            removeFromLocalStorage("cookiesprimarycolor");
            saveToLocalStorage("cookiesprimarycolor", "#0bb2d4");
            $(".dark").css("--primarycolor", "#0bb2d4");
            $(".gradient-img").show();
        }
        saveToLocalStorage("themecolor", $(this).val());
    });

    var themecolor = getUrlParameter("themecolor");
    if (themecolor != null && themecolor != "") {
        saveToLocalStorage("themecolor", themecolor);
    }

    var themecolor = getFromLocalStorage("themecolor");
    if (themecolor != null && themecolor != "") {
        $("body").addClass(themecolor);
        $(".style[value='" + themecolor + "']").prop("checked", true);
        if (themecolor == "semi-dark") {
            $(".sidecolor").show();
        } else {
            $(".sidecolor").hide();
        }
        if (themecolor == "gradient") {
            $(".gradient-img").show();
            var gradientimg = getFromLocalStorage("gradientimg");
            if (gradientimg != null && gradientimg != "") {
                $(
                    ".gbackground, .gradient, .gradient #header-fix, .gradient #header-fix .logo-bar,.gradient .sidebar, .gradient .sidebar .dropdown-menu, .gradient #settings .sidbarchat, .gradient.horizontal-menu #header-fix, .gradient.horizontal-menu .sidebar .sidebar-menu > li.active, .gradient.horizontal-menu .sidebar .sidebar-menu > li:hover, .gradient.horizontal-menu .sidebar .sidebar-menu > li ul, .gradient.compact-menu .sidebar, .gradient .dropdown-menu"
                ).css("background", "url(" + gradientimg + ")");
            }
        }
    }

    ///////////////////////////// Compact Menu /////////////////////////////

    $("input.sidebar").on("click", function () {
        if ($(this).is(":checked")) {
            $("body").addClass("compact-menu");
            $(".smail-icon").hide();

            saveToLocalStorage("sidebarstyle", "compact-menu");
        } else {
            $("body").removeClass("compact-menu");
            removeFromLocalStorage("sidebarstyle");
        }
    });

    var sidebarstyle = getUrlParameter("sidebarstyle");
    if (sidebarstyle != null && sidebarstyle != "") {
        saveToLocalStorage("sidebarstyle", sidebarstyle);
    }

    var sidebarstyle = getFromLocalStorage("sidebarstyle");
    if (sidebarstyle != null && sidebarstyle != "") {
        $("body").addClass(sidebarstyle);
        $(".smail-icon").hide();
        $(".sidebar").prop("checked", true);
    }

    ///////////////////////////// horizontal Layout /////////////////////////////

    $(".horizontallayout").on("click", function () {
        if ($(this).is(":checked")) {
            $("body").addClass("horizontal-menu");
            saveToLocalStorage("horizontal", "horizontal-menu");
            $(".compact").hide();
        } else {
            $("body").removeClass("horizontal-menu");
            removeFromLocalStorage("horizontal");
            $(".compact").show();
        }
    });

    var horizontalstyle = getUrlParameter("horizontal");
    if (horizontalstyle != null && horizontalstyle != "") {
        saveToLocalStorage("horizontal", horizontalstyle);
    }

    var horizontalstyle = getFromLocalStorage("horizontal");
    if (horizontalstyle != null && horizontalstyle != "") {
        $("body").addClass(horizontalstyle);
        $(".horizontallayout").prop("checked", true);
        $(".compact").hide();
    }

    $("#clearLocalStorage").on("click", function() {
        localStorage.clear();
        window.location.reload();
    });
})(jQuery);


function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split("&"),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split("=");

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return null; // Trả về null nếu không tìm thấy tham số trong URL
}
