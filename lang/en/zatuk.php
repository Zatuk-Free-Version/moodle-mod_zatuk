<?php
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
 * Language File.
 *
 * @since     Moodle 2.0
 * @package   mod_zatuk
 * @copyright 2021 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['zatuk'] = 'Zatuk';
$string['clicktoopen'] = 'Click {$a} link to open resource.';
$string['configdisplayoptions'] = 'Select all options that should be available, existing settings are not modified. Hold CTRL key to select multiple fields.';
$string['configframesize'] = 'When a web page or an uploaded file is displayed within a frame, this value is the height (in pixels) of the top frame (which contains the navigation).';
$string['configrolesinparams'] = 'Enable if you want to include localized role names in list of available parameter variables.';
$string['configsecretphrase'] = 'This secret phrase is used to produce encrypted code value that can be sent to some servers as a parameter.  The encrypted code is produced by an md5 value of the current user IP address concatenated with your secret phrase. ie code = md5(IP.secretphrase). Please note that this is not reliable because IP address may change and is often shared by different computers.';
$string['contentheader'] = 'Content';
$string['createurl'] = 'Create a URL';
$string['displayoptions'] = 'Available display options';
$string['displayselect'] = 'Display';
$string['displayselect_help'] = 'This setting, together with the URL file type and whether the browser allows embedding, determines how the URL is displayed. Options may include:
* Automatic - The best display option for the URL is selected automatically
* Embed - The URL is displayed within the page below the navigation bar together with the URL description and any blocks
* Open - Only the URL is displayed in the browser window
* In pop-up - The URL is displayed in a new browser window without menus or an address bar
* In frame - The URL is displayed within a frame below the navigation bar and URL description
* New window - The URL is displayed in a new browser window with menus and an address bar';
$string['displayselectexplain'] = 'Choose display type, unfortunately not all types are suitable for all URLs.';
$string['externalurl'] = 'External URL';
$string['framesize'] = 'Frame height';
$string['invalidstoredurl'] = 'Cannot display this resource, URL is invalid.';
$string['chooseavariable'] = 'Choose a variable...';
$string['invalidurl'] = 'Entered URL is invalid';
$string['modulename'] = 'Zatuk';
$string['modulename_help'] = 'This zatuk module streams your media content to your Moodle users. The media includes video and audio types.
The tool streams video files in HLS format. So, you don’t feel like video-buffering, but you enjoy yourself with uninterrupted streaming with bitby bit loading. Just like your YouTube.  Keep the video files in different view formats like – Thumbnail or Directory.
The same holds with the audio type.
So create content specific to an organization, role, and user.
With the APIs from the tool and a generated token from your Moodle LMS, you can sync the streaming application and the LMS, two ways. That way, you sync your Moodle LMS with the tool and the tool with your LMS.
Use the tool on-premises or on the cloud.
One of the top advantages of this tool is to reduce the load on your browser while fetching the videos. It has a repository from where you can upload files for streaming. And the contents are super safe as there is no scope for data sharing.';
$string['modulename_link'] = 'mod/zatuk/view';
$string['modulenameplural'] = 'Zatuk';
$string['page-mod-url-x'] = 'Any URL module page';
$string['parameterinfo'] = '&amp;parameter=variable';
$string['parametersheader'] = 'URL variables';
$string['parametersheader_help'] = 'Some internal Moodle variables may be automatically appended to the URL. Type your name for the parameter into each text box(es) and then select the required matching variable.';
$string['pluginadministration'] = 'URL module administration';
$string['pluginname'] = 'Zatuk';
$string['popupheight'] = 'Pop-up height (in pixels)';
$string['popupheightexplain'] = 'Specifies default height of popup windows.';
$string['popupwidth'] = 'Pop-up width (in pixels)';
$string['popupwidthexplain'] = 'Specifies default width of popup windows.';
$string['printintro'] = 'Display URL description';
$string['printintroexplain'] = 'Display URL description below content? Some display types may not display description even if enabled.';
$string['rolesinparams'] = 'Include role names in parameters';
$string['search:activity'] = 'Zatuk';
$string['serverurl'] = 'Server URL';
$string['zatuk:addinstance'] = 'Add a new zatuk module';
$string['zatuk:view'] = 'View';
$string['zatuk:canrate'] = 'Rate';
$string['zatuk:create'] = 'Create';
$string['zatuk:viewallvideos'] = 'View all videos';
$string['zatuk:viewuploadedvideos'] = 'View uploaded videos';
$string['zatuk:deletevideo'] = 'Delete video';
$string['zatuk:editingteacher'] = 'Editing teacher';
$string['zatuk:editvideo'] = 'Edit video';
$string['zatuk:manageactions'] = 'Manage actions';
$string['zatuk:uploadvideo'] = 'Upload video';
$string['zatuk:viewuploadedvideo'] = 'View uploaded video';
$string['zatuk:viewvideos'] = 'View videos';
$string['width'] = 'Width';
$string['height'] = 'Height';
$string['zatukanalyticsuser'] = 'Zatuk analytics user';
$string['zatukanalyticsemail'] = 'Zatuk analytics user email';
$string['appearence'] = 'Appearence';
$string['configlocal_review_help'] = 'Enable reviews on the modules';
$string['topviews'] = 'Most viewed';
$string['fivemins'] = 'Most liked ( > 5 minutes)';
$string['fivetotenmins'] = 'Most liked ( > 5 minutes AND < 10 minutes)';
$string['abovetenmins'] = 'Most liked ( > 10 minutes)';
$string['activevideos'] = 'Active/total videos';
$string['streamedvideos'] = 'Streamed minutes';
$string['totalviews'] = 'Total Views';
$string['uploadedvideos'] = 'Videos';
$string['uploadvideo'] = 'Upload video';
$string['organization'] = 'Organization';
$string['title'] = 'Title';
$string['tags'] = 'Tags';
$string['videodescription'] = 'Description';
$string['titlerequired'] = 'Required';
$string['filepath'] = 'Video';
$string['filepathrequired'] = 'Video required';
$string['thumbnail'] = 'Thumbnail';
$string['advancedfields'] = 'Advanced fields';
$string['standard'] = 'standard';
$string['url'] = 'url';
$string['views'] = 'Views';
$string['picture'] = 'picture of ';
$string['user'] = 'User ';
$string['email'] = 'Email ';
$string['rated'] = 'Rated ';
$string['lastviewedon'] = ' Last viewed on ';
$string['view'] = 'View ';
$string['lastviewed'] = 'Last viewed ';
$string['video'] = 'Video ';
$string['date'] = 'Date ';
$string['table'] = 'table';
$string['videoname'] = 'Video name';
$string['browsevideo'] = 'Browse video';
$string['required'] = 'Required';
$string['selectvideo'] = 'Select video';
$string['week'] = 'Week';
$string['month'] = 'Month';
$string['year'] = 'Year';
$string['custom'] = 'Custom';
$string['all'] = 'All';
$string['startdateenddate'] = 'Start date - end date';
$string['filter'] = 'Filter';
$string['activities'] = 'Activities : ';
$string['videossummary'] = 'Videos summary';
$string['course'] = 'Course';
$string['averagetime'] = 'Average time';
$string['uploadedon'] = 'Uploaded on';
$string['uploadedby'] = 'Uploaded by';
$string['graph'] = 'Graph';
$string['reporttable'] = 'Report table';
$string['activitystatus'] = 'Activity status';
$string['status'] = 'Status';
$string['startedon'] = 'Started on';
$string['completedon'] = 'Completed on';
$string['timeperiod'] = 'Time Period';
$string['day'] = 'Day';
$string['completedvideos'] = 'Completed videos';
$string['videotrends'] = 'Video trends';
$string['na'] = 'N/A';
$string['byactivity'] = 'By activity';
$string['selected'] = 'selected';
$string['manager'] = 'manager';
$string['bycourse'] = 'By course';
$string['querywrong'] = 'Sql query Wrong!';
$string['disabled'] = 'disabled="disabled"';
$string['completed'] = 'Completed';
$string['notyetstarted'] = 'Not yet started';
$string['inprogress'] = 'In progress';
$string['notsynced'] = 'Not synced';
$string['syncedat'] = 'Synced at ';
$string['noreport'] = 'Report doesnt exist';
$string['noofviewsbyuser'] = 'No. of views by user';
$string['dailyhitsviews'] = 'Daily hits/Views';
$string['hitsviews'] = 'Hits/views';
$string['noofusers'] = 'Number of users';
$string['tablesearch'] = 'Search...';
$string['eventzatukactivityviewed'] = 'Zatuk activity viewed';
$string['eventvideoplayed'] = 'Video played';
$string['eventvideocompleted'] = 'Video completed';
$string['eventvideopaused'] = 'Video paused';
$string['zatukingapp'] = 'Zatuk app';
$string['recordsession'] = 'Record session';
$string['nozatukrepository'] = 'Please enable zatuk repository to <u><a href="{$a}">continue</a></u>';
$string['completionvideo'] = 'The user must complete the video.';
$string['videocompleted'] = 'The user with id {$a->userid} completely viewed the zatuk activity having reportid {$a->objectid}.';
$string['videoplayed'] = 'The user with id {$a->userid} plays the video having reportid {$a->objectid}.';
$string['zatukpaused'] = 'The user with id {$a->userid} paused the video having reportid {$a->objectid}.';
$string['zatukinstance'] = 'The user with id {$a->userid} viewed the zatuk activity with course moduleid {$a->objectid}.';
$string['user'] = 'User';
$string['video'] = 'Video';
$string['attempts'] = '# of attempts';
$string['status'] = 'Status';
$string['completedon'] = 'Completed on';
$string['lastviewdon'] = 'Last viewed on';
$string['pluginname'] = 'Zatuk';
$string['uploadedvideos'] = 'Uploaded videos';
$string['uploadvideo'] = 'Upload video';
$string['title'] = 'Title';
$string['filepath'] = 'Video';
$string['advancedfields'] = 'Advanced fields';
$string['tags'] = 'Tags';
$string['videodescription'] = 'Description';
$string['thumbnail'] = 'Thumbnail';
$string['filepathrequired'] = 'Video required';
$string['titlerequired'] = 'Required';
$string['totalvideos'] = 'Total videos';
$string['upload_videos'] = 'Upload video';
$string['deleteconfirm'] = 'Are you sure to delete this video?';
$string['deletevideo'] = 'Delete video?';
$string['selectcategory'] = 'Select category';
$string['addvideo'] = 'Add Video';
$string['novideosuploadedyet'] = 'No videos uploaded yet, please upload!';
$string['zatukingnotyetset'] = 'No video available in streaming application, Please make sure streaming application settings are correct <a href='.$CFG->wwwroot.'/admin/repository.php?sesskey={$a}&action=edit&repos=zatuk>Here</a>';
$string['eventvideouploaded'] = 'Video uploaded';
$string['eventvideosynced'] = 'Video synced';
$string['inprogress'] = 'In progress videos';
$string['allvideos'] = 'All videos';
$string['published'] = 'Publised videos';
$string['deletevideo'] = 'Delete video';
$string['uploadeddate'] = 'Uploaded date';
$string['nodata'] = 'No video available with given filters.';
$string['uploadedby'] = 'Uploaded by';
$string['on'] = 'On';
$string['delete'] = 'Delete';
$string['uploadvideo'] = 'Upload video';
$string['publishedon'] = 'Published on';
$string['uploadedon'] = 'Uploaded on';
$string['uploadedvideos'] = 'Uploaded videos:';
$string['syncvideos'] = 'Synced videos:';
$string['totalvideos'] = 'Total videos:';
$string['viewmore'] = 'View More';
$string['saveandcontinue'] = 'Save & continue';
$string['cancel'] = 'Cancel';
$string['cannotcallclass'] = 'Cannot call a class as a function';
$string['expression'] = 'Super expression must either be null or a function, not ';
$string['notinitialised'] = 'this hasnt been initialised - super() hasnt been called';
$string['unloaddetails'] = 'Before unload details updated';
$string['completedduration'] = 'completed duration is 0';
$string['notintrested'] = 'Not inserted';
$string['Pauselog'] = 'Pauselog details updated';
$string['notupdated'] = 'Not updated';
$string['uploaderror'] = 'Error in upload';
$string['public'] = 'Public';
$string['editvideo'] = 'Edit video';
$string['movetozatuk'] = 'Move to zatuk';
$string['enableanalytics'] = 'Enable Analytics';
$string['enableanalyticsdesc'] = 'By default it will be enabled.';
$string['Zatuk'] = 'Zatuk';
$string['settings'] = 'Settings';
$string['movetozatukconfirm'] = 'Are you sure you want to move it to Zatuk?';
$string['actionpermission'] = 'Sorry, but you do not currently have permissions to do that.';
$string['nopermissions'] = 'Sorry, but you do not currently have permissions to do that.';
$string['videouploadedby'] = 'Video is uploaded by userid {$a->userid} with status {$a->objectid}';
$string['views'] = 'Views';
$string['not_synced'] = 'Not synced';
$string['synced_at'] = 'Synced at';
$string['privacy:metadata:organization'] = 'Category under organization';
$string['privacy:metadata:videoid'] = 'Uploded video id';
$string['privacy:metadata:title'] = 'Uploded  video title';
$string['privacy:metadata:description'] = 'Uploded  video description';
$string['privacy:metadata:tags'] = 'Tags created under organization';
$string['privacy:metadata:filename'] = 'Uploaded video file name';
$string['privacy:metadata:filepath'] = 'Uploaded video file path';
$string['privacy:metadata:organisationname'] = 'name of the category under organization';
$string['privacy:metadata:tagsname'] = 'Name of tags under organization';
$string['privacy:metadata:status'] = 'Status of the uploaded video';
$string['privacy:metadata:published'] = 'Published status of the uploaded video';
$string['privacy:metadata:usercreated'] = 'ID of the submission author';
$string['preview'] = 'Preview';
$string['loading'] = 'Loading...';
$string['browse_video'] = 'Browse video';


