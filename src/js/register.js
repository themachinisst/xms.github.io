function displayClientInfo(elem) { 
    // console.log(elem.id);
    const clientElems = document.getElementsByClassName("client");
    const agencyElems = document.getElementsByClassName("agency");

    if(elem.id === 'ClientTypesClient') {
        for (let i = 0; i < agencyElems.length; i++) {
            clientElems[i].style.display = "block";
            agencyElems[i].style.display = "none";
        }
    }else if(elem.id === 'ClientTypesAgency'){
        for (let i = 0; i < agencyElems.length; i++) {
            clientElems[i].style.display = "none";
            agencyElems[i].style.display = "block";
        }
    }else{
        clientElems[i].style.display = "block";
        agencyElems[i].style.display = "block";
    }
}

