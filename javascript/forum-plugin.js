/**
 * Wrapper function to safely use $
 */
function forumWrapper($) {
    let forum = {

        /**
         * Main entry point
         */
        init: function () {
            forum.wrapper = $(".forum-widget-wrapper");
            forum.threadsWrapper = $(".forum-widget-wrapper .threads-wrapper");
            forum.ajaxUrl = forum.threadsWrapper.attr("data-url");
            forum.baseUrl = forum.threadsWrapper.attr("data-base-url");
            forum.imagesUrl = forum.threadsWrapper.attr("data-images-url");

            // Fetch Data from server
            forum.getData();
        },

        /**
         * Render data
         * @param threads
         */
        renderData(threads) {
            threads.forEach((thread) => {
                $(forum.threadsWrapper)
                    .append(
                        '<div class="thread">' +
                        '<div class="inner-icon"> ' +
                        '   <img src="' + forum.imagesUrl + '/question.svg" alt="question"/> ' +
                        '</div> ' +
                        '<div class="thread-link"> ' +
                        '   <a href="' + forum.baseUrl + '/thread/' + thread.slug + '" target="_blank">' + thread.name + '</a>' +
                        '</div> ' +
                        '<div class="messages"> ' +
                        '   <img src="' + forum.imagesUrl + '/messages.svg" alt="messages"/> ' +
                        '   <span class="font-bold">' + thread.posts + '</span> ' +
                        '</div> ' +
                        '</div>')
            })
        },

        /**
         * Get Data
         */
        getData: function () {
            $.get({
                url: forum.ajaxUrl,
                success: (response) => {
                    if ('threads' in response) {
                        this.renderData(response.threads)
                    }
                },
                error: () => {
                    $(forum.threadsWrapper)
                        .html(' <div class="error">\n' +
                            '        <p>There is no records yet.</p>\n' +
                            '    </div>')
                }
            });
        },
    };

    $(document).ready(forum.init);

}

forumWrapper(jQuery);
