/*
* @author: datdb
*/
var FeaturedProduct = {
    checkClick : 1,
    /*
    * submit array product id which were checked
    */
    save : function(){
        // check click to prevent multiple click
        if(FeaturedProduct.checkClick == 0){
           return;
        }
        FeaturedProduct.checkClick == 0;

        // get all selected product id
        var selected = [];
        jQuery('input:checked').each(function() {
            selected.push(jQuery(this).val());
        });

        // get all unselected product id
        var unselected = [];
        jQuery('.checkbox').each(function() {
            unselected.push(jQuery(this).val());
        });

        jQuery('#aryCheckedBoxes').val(selected);
        jQuery('#aryUncheckedBoxes').val(unselected);

        document.getElementById("featured_products").submit();
        FeaturedProduct.checkClick == 1;
    }
}
