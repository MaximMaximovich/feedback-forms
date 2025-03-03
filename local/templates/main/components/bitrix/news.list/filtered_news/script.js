BX.addCustomEvent('BX.Main.Filter:apply', BX.delegate(function () {

    let content = document.getElementById('news-list');

    $.ajax({
        url: window.location.href,
        method: 'post',
        dataType: 'html',
        success: function(data){
            content.innerHTML = $(data).find('#news-list').html();
        }
    });

}));