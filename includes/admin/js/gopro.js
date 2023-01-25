jQuery(document).ready(function (jQuery) {

    var tkgfGoPro = jQuery('a[href="themes.php?page=tk-google-fonts-optionss"]');
    tkgfGoPro.css('color', '#fca300' );

    jQuery( ".tkgf-bundle-list-see-more" ).click(function() {
        jQuery(".tkgf-show-more").animate({
            height: "1100"
        });
        jQuery(".tkgf-pro-plan").height(620);
        jQuery(".separator, .tkgf-bundle-list-see-more").hide();
    });

    jQuery('#tkgf-purchase-pro-plan').on('click', function (e) {

        var handler = FS.Checkout.configure({
            plugin_id:  '426',
            plan_id:    '1631',
            public_key: 'pk_27b7a20f60176ff52e48568808a9e',
        });
        
        handler.open({
            name     : 'Tk Google Fonts',
            licenses : jQuery('#tkgf-pro-license').val(),
            purchaseCompleted  : function (response) {
            },
            success  : function (response) {
            }
        });
        e.preventDefault();
    });

    jQuery('#tkgf-purchase-bundle-plan').on('click', function (e) {

        var handler = FS.Checkout.configure({
            plugin_id:  '2046',
            plan_id:    '4316',
            public_key: 'pk_ee958df753d34648b465568a836aa',
        });
        
        handler.open({
            name     : 'ThemeKraft Membership',
            licenses : jQuery('#tkgf-membership-license').val(),
            purchaseCompleted  : function (response) {
            },
            success  : function (response) {
            }
        });
        e.preventDefault();
    });

    jQuery("select#tkgf-pro-license").change(function () {
        var selectedLicense = jQuery(this).children("option:selected").val();
        if( selectedLicense == '1'){
            jQuery('.tkgf-fs-pro-price').text('39.99');
            jQuery('#tkgf-savings-price-pro').text('119.97');
        }
        if( selectedLicense == '5'){
            jQuery('.tkgf-fs-pro-price').text('69.99');
            jQuery('#tkgf-savings-price-pro').text('199.84');
        }
        if( selectedLicense == 'unlimited'){
            jQuery('.tkgf-fs-pro-price').text('99.99');
            jQuery('#tkgf-savings-price-pro').text('219.99');
        }
    });

    jQuery("select#tkgf-membership-license").change(function () {
        var selectedLicense = jQuery(this).children("option:selected").val();
        if( selectedLicense == '1'){
            jQuery('.tkgf-fs-bundle-price').text('99.99');
            jQuery('#tkgf-savings-price-bundle').text('602.75');
        }
        if( selectedLicense == '5'){
            jQuery('.tkgf-fs-bundle-price').text('119.99');
            jQuery('#tkgf-savings-price-bundle').text('965.75');
        }
        if( selectedLicense == 'unlimited'){
            jQuery('.tkgf-fs-bundle-price').text('129.99');
            jQuery('#tkgf-savings-price-bundle').text('1168.76');
        }
    });

});