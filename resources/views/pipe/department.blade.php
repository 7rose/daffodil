@extends('../layout')
@section('container')

<script src="{{ URL::asset('node_modules/snapsvg/dist/snap.svg-min.js')}}"></script>

{{-- department svg --}}

<div class="col-md-4 col-md-offset-4">
    <div class="panel panel-default">
      	<div class="panel-heading">
	        <i class="glyphicon glyphicon-th-list"></i>&nbsp部门
	    </div>
	    <div class="panel-body">
			{!! form($form) !!}
		</div>
	</div>
</div>



{{-- departmnet svg --}}
<script>
	//(function(){
		var svg = Snap('#svg');

		var init_x=10, init_y=60;
		var w=80, h=30, c=4;

		var padding=10;

		var arr=['a','b','c'];

		
		var c = svg.paper.rect(init_x, init_y, w, h, c).attr({stroke: "#666",strokeWidth:1,fill: "#fff"});
		//svg.select('c').click(function(){ this.animate({ fill:"#666"},1000); });
		//var d = c.clone().attr({fill:"#666"});
		c.click(function(){
			this.animate({x: 100}, 1000, mina.easeout(), function() {
				console.log('animation end');
			});
		});
		

		Snap.load("custom/rhonin/image/logo.svg", function(s) {
		     svg.append(s);
		     //var rose = s;

		});

		for (var i = 1; i < arr.length; i++) {
			var d=c.clone();
			d.animate({y: (h+padding)*i+init_y}, 500, mina.easeout(), function() {
				//console.log('animation end');
			});
		}



	//})();

</script>
{{-- end of department svg --}}

@endsection