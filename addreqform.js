function validation()
{
    var txt= "";

	if(document.getElementById("null").selected)
		txt+="Please select a service "+"\n";
        


        var x =document.getElementById("form");	
    
    
    
    
    if(x.field3.value.length> 50)	
    
    txt+= "Sorry you are not allowed to enter more than 50 character in the Description field" +"\n";	
    
    
    if(txt!="")	
    {
    alert(txt);	
   
   return false;
    }
    else
    { alert("Thank you");	
    //location.href='reqpage.php';
    document.getElementById("form").submit();
	}
  
    }



