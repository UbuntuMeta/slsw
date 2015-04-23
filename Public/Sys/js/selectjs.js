// JavaScript Document
function selAll()
{
	var celements = document.getElementsByName('arcID[]');
	for(i=0;i<celements.length;i++)
	{
		celements[i].checked = true;
	}
}
function sel_no()
{
	var celements = document.getElementsByName('arcID[]');
	for(i=0;i<celements.length;i++)
	{
		if(!celements[i].checked) celements[i].checked = true;
		else celements[i].checked = false;
	}
}

function noSelAll()
{
	var celements = document.getElementsByName('arcID[]');
	for(i=0;i<celements.length;i++)
	{
		celements[i].checked = false;
	}
}

function pnoselAll()
{
	var celements = document.getElementsByName('arcID[]');
	for(i=0;i<celements.length;i++)
	{
		if(celements[i].checked = true) 
		{
			celements[i].checked = false;
		}
	}
}