function autoSubmit(elem)
{
    // console.log(elem);
    var formObject = document.forms[elem.id];
    console.log(elem.id);
    formObject.submit();

    document.getElementById(elem.id).style.display = "none";
}

function showFirstReq(jsArr, start){
    console.log(start);
    for(var i=start; i<jsArr.res.length; i++){
        document.getElementById("question"+jsArr.res[i].QuestionId).style.display  = "none";
        document.getElementById("questionSubmit").style.display  = "none";
    }
}


function navigatePage(questionId, pagenum){

    //as we have questions starting from 0 in questionId array, we'll decrement he page number sent by 1... as the pagenum sent starts from 
    //requirement page as page starts from 0
    // pagenum+=1;
    
    let arrLength = questionId.length;
    console.log("pagenumber sent : "+pagenum+" Array len "+arrLength);

    for(var i=0; i<arrLength; i++){
        // console.log(questionId[i], i===pagenum, i===arrLength);
        if(i === pagenum){
            document.getElementById("question"+questionId[i]).style.display  = "block";
            // console.log("first");
        // }else if(questionId[i] === ){
        //     document.getElementById("questionSubmit").style.display  = "block";
        //     console.log("sec");
        }else{
            document.getElementById("question"+questionId[i]).style.display  = "none";
            document.getElementById("questionSubmit").style.display  = "none";
            // console.log("third");
        }
    }
}
