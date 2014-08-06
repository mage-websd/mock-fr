var Filter = {
    filter : function(min, max, currentPrice, aryCurrentPrice){
        var strVar = document.URL;

        if(strVar.indexOf("?") == -1){
            alert(1);
            var url = strVar + '?price=' + min + '-' + max;
        } else {
            alert(2);
            if(currentPrice == ''){
                alert(3);
                var url = strVar + '&price=' + min + '-' + max;
            } else {
                alert(4);
                var minPrice = aryCurrentPrice['min'];
                var maxPrice = aryCurrentPrice['max'];
                alert(strVar);
                var url2 = strVar.replace(minPrice, min);
                alert(url2);
                var url = url2.replace(maxPrice, max);
                alert(url);
            }
        }

        alert(url);
            window.history.pushState("GET", '', url);
            jQuery("#slider-range").slider({ disabled: true });
            location.reload();
    }
}

//$(#slider-range).mouseup(function(){
//});