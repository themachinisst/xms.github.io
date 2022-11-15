function autoSubmit(elem)
{
    // console.log(elem);
    var formObject = document.forms[elem.id];
    console.log(elem.id);
    formObject.submit();

    document.getElementById(elem.id).style.display = "none";
}
