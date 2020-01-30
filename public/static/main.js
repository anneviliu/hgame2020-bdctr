$("#buttons").click(function() {
    submit();
});

function submit(){
    var id = $("#id").val();
    var chal_name = $("#chal_name").val();
    var level = $("#level").val();
    var time = $("#time").val();

    if(id == "" || chal_name == "" || level == "" || time == ""){
        alert("请填写信息！");
        return;
    }

    var data = "<msg><id>" + id + "</id><name>" + chal_name + "</name><level>" + level + "</level><time>" + time +"</time></msg>";
    $.ajax({
        type: "POST",
        url: "submit.php",
        contentType: "application/xml;charset=utf-8",
        data: data,
        dataType: "xml",
        anysc: false,
        success: function (result) {
            var code = result.getElementsByTagName("code")[0].childNodes[0].nodeValue;
            var msg = result.getElementsByTagName("msg")[0].childNodes[0].nodeValue;
            if(code == "0"){
                alert(msg);
            }else if(code == "1"){
                alert(msg);
            }else{
                alert(msg);
            }
        },
        error: function (XMLHttpRequest,textStatus,errorThrown) {
            alert(errorThrown + ':' + textStatus);
        }
    });
}