function botphonicAlternative($scope) {
    var selectedFile = $scope.find('#jsonFiles').val();
    var $tbody = $scope.find('#jsonData tbody');

    var yesImage = '<img width="26" height="26" decoding="async" style="width: 26px; height: 26px;" src="https://botphonic.ai/wp-content/uploads/2025/06/right.webp" alt="true" class="entered lazyloaded">';
    var noImage = '<img width="26" height="26" decoding="async" style="width: 26px; height: 26px;" src="https://botphonic.ai/wp-content/uploads/2025/06/cross.webp" alt="cross" class="entered lazyloaded">';

    function tableContent(fileName) {
        jQuery.ajax({
            url: fileName,
            type: 'GET',
            dataType: 'json',
            success: function (jsonData) {
                if (!jsonData || !Array.isArray(jsonData.content)) {
                    console.warn('Invalid JSON structure');
                    return;
                }

                $tbody.html(''); // Clear previous rows

                jQuery.each(jsonData.content, function (index, value) {
                    let ourValue = (value.our || '').trim().toLowerCase();
                    let otherValue = (value.other || '').trim().toLowerCase();

                    value.our = (ourValue === "yes") ? yesImage : (ourValue === "no" ? noImage : value.our);
                    value.other = (otherValue === "yes") ? yesImage : (otherValue === "no" ? noImage : value.other);

                    $tbody.append(
                        '<tr>' +
                            '<td class="feature">' + value.key + '</td>' +
                            '<td class="our">' + value.our + '</td>' +
                            '<td class="other">' + value.other + '</td>' +
                        '</tr>'
                    );
                });
            },
            error: function () {
                console.error("Failed to load JSON file:", fileName);
                $tbody.html('<tr><td colspan="3">Unable to load data.</td></tr>');
            }
        });
    }

    if (selectedFile) {
        tableContent(selectedFile);
    }
}

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/botphonic-alternative.default', botphonicAlternative );
});