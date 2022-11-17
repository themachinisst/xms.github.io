function autoSubmit(elem)
{
    // console.log(elem);
    var formObject = document.forms[elem.id];
    console.log(elem.id);
    formObject.submit();
    window.location.reload("welcome.php");

    // document.getElementById(elem.id).style.display = "none";
}