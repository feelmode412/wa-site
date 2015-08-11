<script type="text/javascript">

    // Asset URL, suffix is required, or just fill with ''
    var asset = function (suffix) {
        return "<?= asset(null) ?>" + suffix;
    };

    // App URL
    var url = function (suffix) {
        suffix = (typeof suffix !== "undefined") ? "/" + suffix : "";
        return "<?= url() ?>" + suffix;
    };

</script>