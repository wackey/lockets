function loadPrefecture (){
	var pref = document.getElementById('pref');
	for (var i in AREA){
		var opt = document.createElement("OPTION");
		opt.value = i;
		var txt = document.createTextNode( AREA[i].name );
		opt.appendChild(txt);
		pref.appendChild(opt);
	}
}

function changeLargeArea (pr){
	var ba = document.getElementById('l_area');
	ba.length = 1;
	var sa = document.getElementById('s_area');
	sa.length = 1;
	if(!pr.value){
		return;
	}
	for (i in AREA[ pr.value ].LargeArea){
		var opt = document.createElement("OPTION");
		opt.value = i;
		var name = AREA[ pr.value ].LargeArea[i].name;
		var txt = document.createTextNode( name );
		opt.appendChild(txt);
		ba.appendChild(opt);
	}
}

function changeSmallArea (ba){
	if(!ba.value){
		return;
	}
	var pr = document.getElementById('pref');
	var sa = document.getElementById('s_area');
	sa.length = 1;
	for (var i in AREA[ pr.value ].LargeArea[ ba.value ].SmallArea){
		var opt = document.createElement("OPTION");
		opt.value = i;
		var name = AREA[ pr.value ].LargeArea[ ba.value ].SmallArea[i].name;
		var txt = document.createTextNode( name );
		opt.appendChild(txt);
		sa.appendChild(opt);
	}
}

function initAreaPulldown (in_pr,in_ba,in_sa){
	loadPrefecture();
	if (in_pr){
		var pr = document.getElementById('pref');
		var ba = document.getElementById('l_area');
		var sa = document.getElementById('s_area');
		pr.value = in_pr;
		changeLargeArea(pr);
		ba.value = in_ba;
		changeSmallArea(ba);
		sa.value = in_sa;
	}
}
