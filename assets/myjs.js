$(document).ready(function()
{
	//Right Click Disable Starts
    $(document)[0].oncontextmenu = function(){ return false; }

    $(document).mousedown(function(e)
    {
        if(e.button == 2)
        {
            alert('Sorry, this functionality is disabled!');
            return false;
        }
        else
        {
            return true;
        }
    });
    //Right Click Disable Ends
    
	$(document).keydown(function(e)
	{
		if(e.keyCode == 123)
		{
			return false;//Prevents F12 - Inspect Element
		}
		else if(e.ctrlKey && e.keyCode == 85)
		{
			return false;//Prevents ctrl+u - View Source
		}
		else if(e.ctrlKey && e.shiftKey && e.keyCode == 73)
		{
			return false;//Prevents ctrl+shift+i - Inspect Element
		}
		else if(e.ctrlKey && e.shiftKey && e.keyCode == 74)
		{
			return false;//Prevents ctrl+shift+j - Console
		}
	});

	//Inspect Element Content Hide
	/*var currentInnerHtml;
	var element = new Image();
	var elementWithHiddenContent = document.querySelector("#element-to-hide");
	var innerHtml = elementWithHiddenContent.innerHTML;

	element.__defineGetter__("id", function()
	{
	    currentInnerHtml = "";
	});

	setInterval(function()
	{
	    currentInnerHtml = innerHtml;
	    console.log(element);
	    console.clear();
	    elementWithHiddenContent.innerHTML = currentInnerHtml;
	}, 1000);*/
	
	//Disable Console Script
	/*if(!$('body').hasClass('debug_mode'))
	{
		var _z = console;
		Object.defineProperty(window, "console",
		{
			get: function()
			{
				if((window && window._z && window._z._commandLineAPI) || {})
				{
					throw "Nice trick! but not permitted!";
				}
				return _z;
			},
			set: function(val)
			{
				_z = val;
			}
		});
	}*/
});