function displayClientInfo(elem) { 

    console.log(elem.id);

    if(elem.id === 'ClientTypesClient') {
        alert(document.getElementsByClassName(".client"));
        document.getElementsByClassName(".client").style.display = "block";
        document.getElementsByClassName(".agency").style.display = "none";
    }else{
        alert(document.getElementsByClassName(".agency").style.display);
        document.getElementsByClassName(".client").style.display = "none";
        document.getElementsByClassName(".agency").style.display = "block";
    }
}