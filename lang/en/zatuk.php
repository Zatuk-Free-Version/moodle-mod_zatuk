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
 * @package   mod_zatuk
 * @copyright 2023 Moodle India
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['abovetenmins'] = 'Most liked ( > 10 minutes)';
$string['actionpermission'] = 'Sorry, but you do not currently have permissions to do that.';
$string['activevideos'] = 'Active/total videos';
$string['activities'] = 'Activities : ';
$string['activitystatus'] = 'Activity status';
$string['addvideo'] = 'Add Video';
$string['advancedfields'] = 'Advanced fields';
$string['allvideos'] = 'All videos';
$string['appearence'] = 'Appearence';
$string['attempts'] = '# of attempts';
$string['averagetime'] = 'Average time';
$string['browse_video'] = 'Browse video';
$string['browsevideo'] = 'Browse video';
$string['byactivity'] = 'By activity';
$string['bycourse'] = 'By course';
$string['cannotcallclass'] = 'Cannot call a class as a function';
$string['choose_video'] = 'Choose video';
$string['chooseavariable'] = 'Choose a variable...';
$string['clicktoopen'] = 'Click {$a} link to open resource.';
$string['completedduration'] = 'completed duration is 0';
$string['completedon'] = 'Completed on';
$string['completedvideos'] = 'Completed videos';
$string['completionvideo'] = 'The user must complete the video.';
$string['configframesize'] = 'When a web page or an uploaded file is displayed within a frame, this value is the height (in pixels) of the top frame (which contains the navigation).';
$string['configlocal_review_help'] = 'Enable reviews on the modules';
$string['configrolesinparams'] = 'Enable if you want to include localized role names in list of available parameter variables.';
$string['configsecretphrase'] = 'This secret phrase is used to produce encrypted code value that can be sent to zatuk application as a parameter.  The encrypted code is produced by an md5 value of the current user IP address concatenated with your secret phrase. ie code = md5(IP.secretphrase). Please note that this is not reliable because IP address may change and is often shared by different computers.';
$string['contentheader'] = 'Content';
$string['createurl'] = 'Create a url';
$string['custom'] = 'Custom';
$string['dailyhitsviews'] = 'Daily hits/Views';
$string['deleteconfirm'] = 'Are you sure to delete this video?';
$string['deletevideo'] = 'Delete video';
$string['descriptionhelp'] = 'Description';
$string['descriptionhelp_help'] = 'Zatuk video description';
$string['disabled'] = 'Disabled';
$string['displayselect'] = 'Display';
$string['displayselect_help'] = 'This setting, together with the url file type and whether the browser allows embedding, determines how the url is displayed. Options may include:
* Automatic - The best display option for the URL is selected automatically
* Embed - The url is displayed within the page below the navigation bar together with the url description and any blocks
* Open - Only the url is displayed in the browser window
* In pop-up - The url is displayed in a new browser window without menus or an address bar
* In frame - The url is displayed within a frame below the navigation bar and URL description
* New window - The url is displayed in a new browser window with menus and an address bar';
$string['displayselectexplain'] = 'Choose display type, unfortunately not all types are suitable for all urls.';
$string['editvideo'] = 'Edit video';
$string['enableanalytics'] = 'Enable Analytics';
$string['enableanalyticsdesc'] = 'By default it will be enabled.';
$string['enablezatuk'] = 'Please enable zatuk repository.';
$string['eventvideocompleted'] = 'Video completed';
$string['eventvideodeleted'] = 'Video deleted';
$string['eventvideopaused'] = 'Video paused';
$string['eventvideoplayed'] = 'Video played';
$string['eventvideosynced'] = 'Video synced';
$string['eventvideouploaded'] = 'Video uploaded';
$string['eventzatukactivityviewed'] = 'Zatuk activity viewed';
$string['expression'] = 'Super expression must either be null or a function, not ';
$string['externalurl'] = 'External url';
$string['failedwarningmessage']  = '<div class="d-flex justify-content-center align-items-center
                        flex-column w-100 p-3 zatukconfirmationfaileddialogue_content">
                        <div class="icon"></div><h4 class="my-3">{$a}</h4></div>';
$string['filepath'] = 'Video';
$string['filepathhelp'] = 'zatuk video.';
$string['filepathhelp_help'] = 'The actual video file that you want to upload to the platform.';
$string['filepathrequired'] = 'Video required';
$string['filter'] = 'Filter';
$string['finalzatuksmessage'] = '<div class="d-flex justify-content-center align-items-center
                                flex-column w-100 p-3 zatuknodatadialogue_content">
                                <div class="icon"></div><h4 class="my-3">{$a}</h4></div>';
$string['fivemins'] = 'Most liked ( > 5 minutes)';
$string['fivetotenmins'] = 'Most liked ( > 5 minutes AND < 10 minutes)';
$string['framesize'] = 'Frame height';
$string['graph'] = 'Graph';
$string['height'] = 'Height';
$string['hitsviews'] = 'Hits/views';
$string['invalidstoredurl'] = 'Cannot display this resource, url is invalid.';
$string['invalidurl'] = 'Entered url is invalid';
$string['lastviewdon'] = 'Last viewed on';
$string['lastviewed'] = 'Last viewed ';
$string['lastviewedon'] = ' Last viewed on ';
$string['loading'] = 'Loading...';
$string['manager'] = 'manager';
$string['modulename'] = 'Zatuk';
$string['modulename_help'] = 'This zatuk module streams your media content to your Moodle users. The media includes video and audio types.
The tool streams video files in HLS format. So, you don’t feel like video-buffering, but you enjoy yourself with uninterrupted streaming with bitby bit loading. Just like your YouTube.  Keep the video files in different view formats like – Thumbnail or Directory.
The same holds with the audio type.
So create content specific to an organization, role, and user.
With the APIs from the tool and a generated token from your Moodle LMS, you can sync the zatuk application and the LMS, two ways. That way, you sync your Moodle LMS with the tool and the tool with your LMS.
Use the tool on-premises or on the cloud.
One of the top advantages of this tool is to reduce the load on your browser while fetching the videos. It has a repository from where you can upload files for streaming. And the contents are super safe as there is no scope for data sharing.';
$string['modulename_link'] = 'mod/zatuk/view';
$string['modulenameplural'] = 'Zatuk';
$string['month'] = 'Month';
$string['movetozatuk'] = 'Move to zatuk';
$string['movetozatukconfirm'] = 'Are you sure you want to move it to Zatuk?';
$string['na'] = 'N/A';
$string['nodata'] = 'No video available with given filters.';
$string['noofusers'] = 'Number of users';
$string['noofviewsbyuser'] = 'No. of views by user';
$string['nopermissions'] = 'Sorry, but you do not currently have permissions to do that.';
$string['norecordsmessage'] = 'No Records Found.';
$string['noreport'] = 'Report doesnt exist';
$string['not_synced'] = 'Not synced';
$string['notintrested'] = 'Not inserted';
$string['notsynced'] = 'Not synced';
$string['notupdated'] = 'Not updated';
$string['notyetstarted'] = 'Not yet started';
$string['novideosuploadedyet'] = 'No video uploaded yet, please upload!';
$string['nozatukrepository'] = 'Please enable zatuk repository to <u><a href="{$a}">continue</a></u>';
$string['on'] = 'On';
$string['organization'] = 'Organization';
$string['organizationzatuk'] = 'Organization category';
$string['parameterinfo'] = '&amp;parameter=variable';
$string['pausedetailesmessage'] = 'Pauselog Details updated';
$string['pluginadministration'] = 'Zatuk module administration';
$string['pluginname'] = 'Zatuk';
$string['privacy:metadata'] = 'The free zatuk resource plugin does not store any personal data.';
$string['public'] = 'Public';
$string['publichelp'] = 'public';
$string['publichelp_help'] = 'If the “public” option is selected, the video will be accessible to all users on the platform. If unchecked, only the uploading teacher, admin can have the access to see the video  and students will have access to the video after published.';
$string['published'] = 'Publised videos';
$string['publishedon'] = 'Published on';
$string['publishedtoserver'] = 'Successfully published to the zatuk application.';
$string['queryexception'] = 'There is an issue with query.';
$string['rated'] = 'Rated ';
$string['recordsession'] = 'Record session';
$string['rolesinparams'] = 'Include role names in parameters';
$string['saveandcontinue'] = 'Save & continue';
$string['search:activity'] = 'Zatuk';
$string['selectcategory'] = 'Select category';
$string['selected'] = 'selected';
$string['selectvideo'] = 'Select video';
$string['serverurl'] = 'Server url';
$string['startdateenddate'] = 'Start date - end date';
$string['startedon'] = 'Started on';
$string['streamedvideos'] = 'Streamed minutes';
$string['synced_at'] = 'Synced at';
$string['syncvideos'] = 'Synced videos:';
$string['table'] = 'Table';
$string['tablesearch'] = 'Search...';
$string['tagszatukhelp'] = 'Organization tags ';
$string['thumbnail'] = 'Thumbnail';
$string['timeperiod'] = 'Time Period';
$string['title'] = 'Title';
$string['titlehelp'] = 'Title';
$string['titlehelp_help'] = 'Choose a descriptive name that reflects the content of the video. This helps in organizing and identifying videos later.';
$string['topviews'] = 'Most viewed';
$string['totalvideos'] = 'Total videos';
$string['totalviews'] = 'Total Views';
$string['unloaddetails'] = 'Before unload details updated';
$string['update_video'] = 'Update video';
$string['upload_videos'] = 'Upload video';
$string['uploadedby'] = 'Uploaded by';
$string['uploadeddate'] = 'Uploaded date';
$string['uploadedon'] = 'Uploaded on';
$string['uploadedvideos'] = 'Uploaded videos';
$string['uploaderror'] = 'Error in upload';
$string['uploadvideo'] = 'Upload video';
$string['uploadzatukvideo'] = 'Please upload zatuk video to create an activity.';
$string['value'] = 'Value ';
$string['video'] = 'Video ';
$string['videocompleted'] = 'The user with id {$a->userid} completely viewed the zatuk activity having reportid {$a->objectid}.';
$string['videodeleted'] = 'Video deleted successfully.';
$string['videodeletedby'] = 'Video is deleted by userid {$a->userid} with status {$a->objectid}';
$string['videodescription'] = 'Description';
$string['videoname'] = 'Video name';
$string['videoplayed'] = 'The user with id {$a->userid} plays the video having reportid {$a->objectid}.';
$string['videos'] = 'Videos ';
$string['videossummary'] = 'Videos summary';
$string['videotrends'] = 'Video trends';
$string['videoupdated'] = 'Zatuk video updated successfully.';
$string['videouploaded'] = 'Zatuk video uploaded successfully.';
$string['videouploadedby'] = 'Video is uploaded by userid {$a->userid} with status {$a->objectid}';
$string['view'] = 'View ';
$string['views'] = 'Views';
$string['width'] = 'Width';
$string['wrongmimetypedetected'] = 'Wrong mime type selected.';
$string['zatuk'] = 'Zatuk';
$string['zatuk:accessedbyfaculty'] = 'Accessed by editing teacher';
$string['zatuk:addinstance'] = 'Add a new zatuk module';
$string['zatuk:canrate'] = 'Rate';
$string['zatuk:create'] = 'Create';
$string['zatuk:deletevideo'] = 'Delete video';
$string['zatuk:deletevideos']  = 'Delete zatuk videos.';
$string['zatuk:editvideo'] = 'Edit video';
$string['zatuk:encryption'] = 'Encryption';
$string['zatuk:myaddinstance'] = 'Add a new zatuk module';
$string['zatuk:uploadvideo'] = 'Upload video';
$string['zatuk:view'] = 'View';
$string['zatuk:viewasmanager'] = 'View as manager';
$string['zatuk:viewuploadedvideo'] = 'View uploaded video';
$string['zatuk:viewuploadedvideos'] = 'View uploaded videos';
$string['zatuk:viewzatukmodule'] = 'View zatuk module';
$string['zatukanalyticsemail'] = 'Zatuk analytics user email';
$string['zatukanalyticsuser'] = 'Zatuk analytics user';
$string['zatukcontent'] = 'Zatuk content';
$string['zatukingapp'] = 'Zatuk app';
$string['zatukingnotyetset'] = 'No video available in zatuk application, Please make sure zatuk application settings are correct';
$string['zatukinstance'] = 'The user with id {$a->userid} viewed the zatuk activity with course moduleid {$a->objectid}.';
$string['zatukpaused'] = 'The user with id {$a->userid} paused the video having reportid {$a->objectid}.';

