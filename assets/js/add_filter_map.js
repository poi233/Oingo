var addFilterMap = {
	map: null,
	markers: [],
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

		this.map = new google.maps.Map(document.getElementById("add_filter_map"), myOptions);

		google.maps.event.addListener(this.map, 'click', function (event) {//点击时出现的提示窗口，这里显示经纬度
			addFilterMap.delMarker();
			var Latitude = event.latLng.lat().toFixed(5);
			var longitude = event.latLng.lng().toFixed(5);
			addFilterMap.addMarker(event.latLng, "name", "<b>Location</b><br>" + Latitude + "," + longitude,
				Latitude + "," + longitude);
		});

		var myLatlng = new google.maps.LatLng(40.69289,-73.98488);
		this.addMarker(myLatlng, "name","<b>Location</b><br>" + myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5),
			myLatlng.lat().toFixed(5) + "," + myLatlng.lng().toFixed(5))
		// google.maps.event.trigger(this.markers[0], 'click');

		//google.maps.event.addListener(this.map, 'click', function (event) {
		//    console.log("Latitude: " + event.latLng.lat() + " " + ", longitude: " + event.latLng.lng());
		//});
	},

	addMarker: function (Gpoint, name, contentString, geo) {//添加地图上的标记
		this.delMarker();
		marker = new google.maps.Marker({
			id: 0,
			position: Gpoint,
			geo: geo,
			map: addFilterMap.map,
			draggable: false,
			animation: google.maps.Animation.DROP
		});

		google.maps.event.addListener(marker, 'click', function () {//添加标记
			addFilterMap.infowindow.setPosition(this.position);
			addFilterMap.infowindow.setContent(contentString);
			addFilterMap.infowindow.open(addFilterMap.map, marker);
		});

		addFilterMap.map.panTo(Gpoint);

		this.markers[0] = marker;

		$("#lat").val(Gpoint.lat().toFixed(5));
		$("#lng").val(Gpoint.lng().toFixed(5));
	},

	delMarker: function () {//删除标记
		for (var i = 0; i < this.markers.length; i++) {
			this.markers[i].setMap(null);
			delete this.markers[i];
		}
		this.markers = []
	}
};


