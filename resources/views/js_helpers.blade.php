<script type="text/javascript">
	var url = function(suffix) {
		suffix = (typeof suffix !== "undefined") ? suffix : "";
		return "{{ url() }}/" + suffix;
	};
	var asset = function(suffix) {
		return "{{ asset(null) }}" + suffix;
	};
</script>