var noteMap = {
	map: null,
	myMark: null,
	note_markers:{},
	currentId: 0,

	infowindow: new google.maps.InfoWindow({
		size: new google.maps.Size(150, 50)
	}),

	initialize: function () {
		if (this.map) return null;

		var myOptions = {
			zoom: 15,//放大的倍数
			center: {lat: 40.69289, lng: -73.98488},
			mapTypeControl: true,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
			navigationControl: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		this.map = new google.maps.Map(document.getElementById("note_map"), myOptions);

		var myLatlng = new google.maps.LatLng(40.69289,-73.98488);
		this.addMyMarker(myLatlng, "name","<b>My Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5));
		google.maps.event.trigger(this.myMark, 'click');
		},

	addNoteMarker: function (Gpoint, id, content) {//添加地图上的标记
		var marker = new google.maps.Marker({
			id: 0,
			position: Gpoint,
			// geo: geo,
			map: noteMap.map,
			draggable: false,
			animation: google.maps.Animation.DROP
		});

		google.maps.event.addListener(marker, 'click', function () {//添加标记
			noteMap.infowindow.setPosition(this.position);
			noteMap.infowindow.setContent(content);
			noteMap.infowindow.open(noteMap.map, marker);
		});

		// noteMap.map.panTo(Gpoint);

		this.note_markers[id] = marker;
	},

	delNoteMarker: function (id) {//删除标记
		this.note_markers[id].setMap(null);
		delete this.note_markers[id];
	},


	addMyMarker: function (Gpoint, name, contentString, geo) {//添加地图上的标记
		if (this.myMark != null)
			this.delMyMarker();
		var marker = new google.maps.Marker({
			id: 0,
			position: Gpoint,
			geo: geo,
			map: noteMap.map,
			draggable: false,
			animation: google.maps.Animation.DROP
		});

		google.maps.event.addListener(marker, 'click', function () {//添加标记
			noteMap.infowindow.setPosition(this.position);
			noteMap.infowindow.setContent(contentString);
			noteMap.infowindow.open(noteMap.map, marker);
		});

		noteMap.map.panTo(Gpoint);

		this.myMark = marker;
	},

	delMyMarker: function () {//删除标记
		this.myMark.setMap(null);
		delete this.myMark;
		this.myMark = null
	}
};


