var Filter = {
    filter: function (min, max, currentPrice, aryCurrentPrice) {
        var strVar = document.URL;

        if (strVar.indexOf("?") == -1) {
            var url = strVar + '?price=' + min + '-' + max;
        } else {
            if (currentPrice == '') {
                var url = strVar + '&price=' + min + '-' + max;
            } else {
                var minPrice = 'price=' + aryCurrentPrice['min'];
                min = 'price=' + min;
                var maxPrice = aryCurrentPrice['max'];
                var url2 = strVar.replace(minPrice, min);
                var url = url2.replace(maxPrice, max);
            }
        }
        window.history.pushState("GET", '', url);
        setLocation(url);
    }
}
