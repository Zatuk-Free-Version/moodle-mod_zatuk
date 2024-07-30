// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines zatuk content script.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(
[
    'jquery',
    'core/notification',
    'core/templates',
    'core/paged_content_factory',
    'core/str',
    'mod_zatuk/zatuk_repository',
    'mod_zatuk/renderzatuk',
    'core/custom_interaction_events',
    'core/modal_factory'
],
function(
    $,
    Notification,
    Templates,
    PagedContentFactory,
    Str,
    zatukVideosRepository,
    RenderZatuk,
    CustomEvents,
    ModalFactory
) {

    const confirmbox = (message) => {
     ModalFactory.create({
        body: message,
        type: ModalFactory.types.ALERT,
        buttons: {
            ok: Str.get_string('Thank_you'),
        },
        removeOnClose: true,
      })
      .done(function(modal) {
        modal.show();
      });
    };
    var length, videosOffset, StatusFilter, SortFilter, SearchFilter;
    var limit = 10;
    var SELECTORS = {
        EMPTY_MESSAGE: '[data-region="empty-message"]',
        ROOT: '[data-region="zatuk-list-container"]',
        ZATUK_LIST_CONTENT: '[data-region="zatuk-list-content"]',
        ZATUK_LIST_LOADING_PLACEHOLDER: '[data-region="zatuk-list-loading-placeholder"]',
        FILTERS: '[data-region="filter"]',
        FILTER_OPTION: '[data-filter]',
        SEARCH_QUERY: '[data-filter="searchfilter"]'
    };
    var TEMPLATES = {
        ZATUK_LIST_CONTENT: 'mod_zatuk/video_list_item'
    };
    var DEFAULT_PAGED_CONTENT_CONFIG = {
        pagingbar: true,
        ignoreControlWhileLoading: true,
        controlPlacementBottom: true,
        ariaLabels: {
            itemsperpagecomponents: 'ariaeventlistpagelimit, mod_timeline',
        }
    };
    /**
     * Hide the content area and display the empty content message.
     *
     * @param {object} root The container element
     */
    var hideContent = function(root) {
        root.find(SELECTORS.ZATUK_LIST_CONTENT).addClass('hidden');
        root.find(SELECTORS.EMPTY_MESSAGE).removeClass('hidden');
    };
    /**
     * Show the content area and hide the empty content message.
     *
     * @param {object} root The container element
     */
    var showContent = function(root) {
        root.find(SELECTORS.ZATUK_LIST_CONTENT).removeClass('hidden');
        root.find(SELECTORS.EMPTY_MESSAGE).addClass('hidden');
    };
    /**
     * Empty the content area.
     *
     * @param {object} root The container element
     */
    var emptyContent = function(root) {
        root.find(SELECTORS.ZATUK_LIST_CONTENT).empty();
    };
    /**
     * Get the default context to render the paged content mustache
     * template.
     * @return {object}
     */
    var getDefaultTemplateContext = function() {
        return {
            pagingbar: true,
            pagingdropdown: true,
            skipjs: true,
            ignorecontrolwhileloading: true,
            controlplacementbottom: false
        };
    };
    /**
     * @param {array} zatukVideos List of calendar events
     * @return {object}
     */
    var buildTemplateContext = function(zatukVideos) {
        var templateContext = getDefaultTemplateContext();
        templateContext.videos = [];

        zatukVideos.forEach(function(zatukVideo) {
            templateContext.videos.push(zatukVideo);
        });
        return templateContext;
    };
    /**
     * Render the HTML for the given calendar events.
     *
     * @param {array} zatukVideos  A list of calendar events
     * @return {promise} Resolved with HTML and JS strings.
     */
    var render = function(zatukVideos) {
        var templateContext = buildTemplateContext(zatukVideos);
        templateContext.itemsperpage = 10;
        var templateName = TEMPLATES.ZATUK_LIST_CONTENT;

        return Templates.render(templateName, templateContext);
    };

    var load = function( limit, videosOffset, videosLimit, lastId, StatusFilter) {
        return zatukVideosRepository.uploadedVideos({
          statusfilter: StatusFilter,
          sort: SortFilter,
          limit: limit,
          search: SearchFilter,
          offset: videosOffset
        });
    };
    var updatePreferences = function() {
        StatusFilter = $(SELECTORS.ROOT).attr('data-statusfilter');
        SearchFilter = $(SELECTORS.ROOT).attr('data-searchquery');

        var args = {
          statusfilter: StatusFilter,
          search: SearchFilter,
          limit: limit,
          offset: videosOffset
        };


        return zatukVideosRepository.updatePreferences(args);
    };
    /**
     * Event listener for the Display filter (cards, list).
     *
     * @param {object} root The root element for the overview mod
     */
    var registerSelector = function(root) {
        if(typeof root != 'undefined'){
          root = $(root);
        }else{
            root = $(SELECTORS.ROOT);
        }

        var Selector = root.find(SELECTORS.FILTERS);
        var searchInput = root.find(SELECTORS.SEARCH_QUERY);
        CustomEvents.define(Selector, [CustomEvents.events.activate]);
        $('#id_search').on('click', function(){
            var query = $(searchInput).val();
            root.attr('data-searchquery', query);
            var preferences = updatePreferences();
                preferences.then(function(result) {
                    root.attr('data-length', result.length);
                    init();
                });
        });
        Selector.on(
            CustomEvents.events.activate,
            SELECTORS.FILTER_OPTION,
            function(e, data) {
                var option = $(e.target);
                var filter = option.attr('data-filter');
                root.attr('data-' + filter, option.attr('data-value'));
                var preferences = updatePreferences();
                preferences.then(function(result) {
                    root.attr('data-length', result.length);
                    init();
                });
                data.originalEvent.preventDefault();
            }
        );
    };
    /**
     * Handle a single page request from the paged content. Uses the given page data to request
     * the events from the server.
     *
     * Checks the given preloadedPages before sending a request to the server to make sure we
     * don't load data unnecessarily.
     *
     * @param {object} pageData A single page data (see core/paged_content_pages for more info).
     * @param {object} actions Paged content actions (see core/paged_content_pages for more info).
     * @param {object} lastIds The last event ID for each loaded page. Page number is key, id is value.
     * @param {object} preloadedPages An object of preloaded page data. Page number as key, data promise as value.
     * @param {int|undefined} StatusFilter Course ID to restrict events to
     * @param {Number} videosOffset How many days (from midnight) to offset the results from
     * @param {int|undefined} videosLimit How many dates (from midnight) to limit the result to
     * @return {object} jQuery promise resolved with calendar events.
     */
    var loadEventsFromPageData = function(
        pageData,
        actions,
        lastIds,
        preloadedPages,
        StatusFilter,
        videosOffset,
        videosLimit
    ) {

        var pageNumber = pageData.pageNumber;
        var limit = pageData.limit;
        var lastPageNumber = pageNumber;
        var videosOffset = (pageNumber-1) * videosLimit;

        // This is here to protect us if, for some reason, the pages
        // are loaded out of order somehow and we don't have a reference
        // to the previous page. In that case, scan back to find the most
        // recent page we've seen.
        while (!lastIds.hasOwnProperty(lastPageNumber)) {
            lastPageNumber--;
        }
        // Use the last id of the most recent page.
        var lastId = lastIds[lastPageNumber];
        var eventsPromise = null;

        if (preloadedPages && preloadedPages.hasOwnProperty(pageNumber)) {
            // This page has been preloaded so use that rather than load the values
            // again.
            eventsPromise = preloadedPages[pageNumber];
        } else {
            // Load one more than the given limit so that we can tell if there
            // is more content to load after this.
            eventsPromise = load(limit + 1, videosOffset, videosLimit, lastId, StatusFilter);
        }

        return eventsPromise.then(function(result) {
            if (!result.length) {
                // If we didn't get any events back then tell the paged content
                // that we're done loading.
                actions.allItemsLoaded(pageNumber);
                return [];
            }
            var zatukVideos = result.data;
            return zatukVideos;
        });
    };
    /**
     * Use the paged content factory to create a paged content element for showing
     * the event list. We only provide a page limit to the factory because we don't
     * know exactly how many pages we'll need. This creates a paging bar with just
     * next/previous buttons.
     *
     * This function specifies the callback for loading the event data that the user
     * is requesting.
     *
     * @param {int|array} length
     * @param {int|array} pageLimit A single limit or list of limits as options for the paged content
     * @param {object} preloadedPages An object of preloaded page data. Page number as key, data promise as value.
     * @param {object} firstLoad A jQuery promise to be resolved after the first set of data is loaded.
     * @param {int|undefined} StatusFilter Course ID to restrict events to
     * @param {Number} videosOffset How many days (from midnight) to offset the results from
     * @param {string} paginationAriaLabel String to set as the aria label for the pagination bar.
     * @param {object} additionalConfig Additional config options to pass to pagedContentFactory
     * @return {object} jQuery promise.
     */
    var createPagedContent = function(
        length,
        pageLimit,
        preloadedPages,
        firstLoad,
        StatusFilter,
        videosOffset,
        paginationAriaLabel,
        additionalConfig
    ) {
        // Remember the last event id we loaded on each page because we can't
        // use the offset value since the backend can skip events if the user doesn't
        // have the capability to see them. Instead we load the next page of events
        // based on the last seen event id.
        var lastIds = {'1': 0};
        var hasContent = false;
        var config = $.extend({}, DEFAULT_PAGED_CONTENT_CONFIG, additionalConfig);

        return Str.get_string(
                'ariaeventlistpagelimit',
                'mod_timeline',
                $.isArray(pageLimit) ? pageLimit[0].value : pageLimit
            )
            .then(function(string) {
                config.ariaLabels.itemsperpage = string;
                config.ariaLabels.paginationnav = paginationAriaLabel;
                return string;
            })
            .then(function() {
                return PagedContentFactory.createWithTotalAndLimit(length,
                    pageLimit,
                    function(pagesData, actions) {
                        var promises = [];
                        pagesData.forEach(function(pageData) {
                            var pagePromise = loadEventsFromPageData(
                                pageData,
                                actions,
                                lastIds,
                                preloadedPages,
                                StatusFilter,
                                videosOffset,
                                pageLimit
                            ).then(function(zatukVideos) {
                                if (zatukVideos.length) {
                                    // Remember that we've loaded content.
                                    hasContent = true;
                                    return render(zatukVideos);
                                } else {
                                    return zatukVideos;
                                }
                            })
                            .catch(Notification.exception);
                            promises.push(pagePromise);
                        });
                        $.when.apply($, promises).then(function() {
                            // Tell the calling code that the first page has been loaded
                            // and whether it contains any content.
                            firstLoad.resolve(hasContent);
                            return;
                        })
                        .catch(function() {
                            firstLoad.resolve(hasContent);
                        });

                        return promises;
                    },
                    config
                );
            });
    };
    /**
     * Create a paged content region for the calendar events in the given root element.
     * The content of the root element are replaced with a new paged content section
     * each time this function is called.
     *
     * This function will be called each time the offset or limit values are changed to
     * reload the event list region.
     *
     * @param {object} root The event list container element
     * @param {int|array} pageLimit A single limit or list of limits as options for the paged content
     * @param {object} preloadedPages An object of preloaded page data. Page number as key, data promise as value.
     * @param {string} paginationAriaLabel String to set as the aria label for the pagination bar.
     * @param {object} additionalConfig Additional config options to pass to pagedContentFactory
     */
    var init = function(root, pageLimit = 10, preloadedPages, paginationAriaLabel, additionalConfig) {
        if(typeof root != 'undefined'){
            root = $(root);
        }else{
            root = $(SELECTORS.ROOT);
        }
        // Create a promise that will be resolved once the first set of page
        // data has been loaded. This ensures that the loading placeholder isn't
        // hidden until we have all of the data back to prevent the page elements
        // jumping around.
        var firstLoad = $.Deferred();
        var zatukListContent = root.find(SELECTORS.ZATUK_LIST_CONTENT);
        var loadingPlaceholder = root.find(SELECTORS.ZATUK_LIST_LOADING_PLACEHOLDER);
         length = root.attr('data-length');
         videosOffset = root.attr('data-videosOffset');
         StatusFilter = root.attr('data-statusfilter');
         SortFilter = root.attr('data-sortfilter');
         SearchFilter = root.attr('data-searchquery');
        // Make sure the content area and loading placeholder is visible.
        // This is because the init function can be called to re-initialise
        // an existing event list area.
        emptyContent(root);
        showContent(root);
        loadingPlaceholder.removeClass('hidden');
        // Created the paged content element.
        return createPagedContent(length,pageLimit, preloadedPages, firstLoad, StatusFilter, videosOffset,
                paginationAriaLabel,  additionalConfig)
            .then(function(html, js) {
                html = $(html);
                // Hide the content for now.
                html.addClass('hidden');
                // Replace existing elements with the newly created paged content.
                // If we're reinitialising an existing event list this will replace
                // the old event list (including removing any event handlers).
                Templates.replaceNodeContents(zatukListContent, html, js);

                firstLoad.then(function(hasContent) {
                    // Prevent changing page elements too much by only showing the content
                    // once we've loaded some data for the first time. This allows our
                    // fancy loading placeholder to shine.
                    html.removeClass('hidden');
                    loadingPlaceholder.addClass('hidden');
                    if (!hasContent) {
                        // If we didn't get any data then show the empty data message.
                        hideContent(root);
                        var confirmA = '<div class="d-flex justify-content-center align-items-center';
                        var confirmB = 'flex-column w-100 p-3 zatuknodatadialogue_content">';
                        var confirmC = '<div class="icon"></div><h4 class="my-3"> No Records Found.</h4></div>';
                        confirmbox(confirmA + confirmB + confirmC);
                    }
                    return hasContent;
                })
                .catch(function() {
                    return false;
                });

                return html;
            })
            .catch(Notification.exception);
    };
    return {
        init: init,
        rootSelector: SELECTORS.ROOT,
        registerSelector: registerSelector
    };
});
