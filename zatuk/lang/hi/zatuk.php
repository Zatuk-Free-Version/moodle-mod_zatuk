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
 * @since Moodle 2.0
 * @package    mod_zatuk
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['clicktoopen'] = 'क्लिक
{$a}
संसाधन खोलने के लिए लिंक।	';
$string['configdisplayoptions'] = 'उपलब्ध होने वाले सभी विकल्पों का चयन करें, मौजूदा सेटिंग्स संशोधित नहीं हैं। एकाधिक फ़ील्ड चुनने के लिए CTRL कुंजी दबाए रखें।	';
$string['configframesize'] = '	जब एक वेब पेज या अपलोड की गई फ़ाइल एक फ्रेम के भीतर प्रदर्शित होती है, तो यह मान शीर्ष फ्रेम (जिसमें नेविगेशन होता है) की ऊंचाई (पिक्सेल में) होती है।	';
$string['configrolesinparams'] = '	सक्षम करें यदि आप उपलब्ध पैरामीटर चर की सूची में स्थानीयकृत भूमिका नाम शामिल करना चाहते हैं।	';
$string['configsecretphrase'] = '	इस गुप्त वाक्यांश का उपयोग एन्क्रिप्टेड कोड मान उत्पन्न करने के लिए किया जाता है जिसे कुछ सर्वरों को पैरामीटर के रूप में भेजा जा सकता है। एन्क्रिप्टेड कोड आपके गुप्त वाक्यांश के साथ जुड़े वर्तमान उपयोगकर्ता आईपी पते के एमडी 5 मान द्वारा निर्मित होता है। यानी कोड = md5 (IP.secretphrase)। कृपया ध्यान दें कि यह विश्वसनीय नहीं है क्योंकि आईपी पता बदल सकता है और अक्सर विभिन्न कंप्यूटरों द्वारा साझा किया जाता है।	';
$string['contentheader'] = '	सामग्री	';
$string['createurl'] = '	एक यूआरएल बनाएं	';
$string['displayoptions'] = '	उपलब्ध प्रदर्शन विकल्प	';
$string['displayselect'] = '	प्रदर्शन	';
$string['displayselect_help'] = '	यह सेटिंग, URL फ़ाइल प्रकार के साथ और क्या ब्राउज़र एम्बेड करने की अनुमति देता है, यह निर्धारित करता है कि URL कैसे प्रदर्शित होता है। विकल्पों में शामिल हो सकते हैं: * स्वचालित - URL के लिए सबसे अच्छा प्रदर्शन विकल्प स्वचालित रूप से चुना जाता है* एम्बेड करें - URL नेविगेशन बार के नीचे पृष्ठ के भीतर URL विवरण और किसी भी ब्लॉक के साथ प्रदर्शित होता है* खुला - ब्राउज़र में केवल URL प्रदर्शित होता है विंडो* पॉप-अप में - URL बिना मेनू या एड्रेस बार के एक नई ब्राउज़र विंडो में प्रदर्शित होता है* फ्रेम में - URL नेविगेशन बार और URL विवरण के नीचे एक फ्रेम के भीतर प्रदर्शित होता है* नई विंडो - URL एक में प्रदर्शित होता है मेनू और एड्रेस बार के साथ नई ब्राउज़र विंडो	';
$string['displayselectexplain'] = '	प्रदर्शन प्रकार चुनें, दुर्भाग्य से सभी प्रकार सभी URL के लिए उपयुक्त नहीं हैं।	';
$string['externalurl'] = '	वीडियो ब्राउज़ करें	';
$string['framesize'] = '	फ्रेम की ऊंचाई	';
$string['invalidstoredurl'] = '	इस संसाधन को प्रदर्शित नहीं कर सकता, URL अमान्य है।	';
$string['chooseavariable'] = '	एक वैरिएबल चुनें...	';
$string['invalidurl'] = '	डाला गया यूआरएल अमान्य है	';
$string['modulename'] = '	धारा	';
$string['modulename_help'] = '	यह स्ट्रीम मॉड्यूल आपकी मीडिया सामग्री को आपके मूडल उपयोगकर्ताओं के लिए स्ट्रीम करता है। मीडिया में वीडियो और ऑडियो प्रकार शामिल हैं।
उपकरण एचएलएस प्रारूप में वीडियो फ़ाइलों को स्ट्रीम करता है। तो, आप वीडियो-बफरिंग की तरह महसूस नहीं करते हैं, लेकिन आप थोड़ा-थोड़ा लोड करके निर्बाध स्ट्रीमिंग का आनंद लेते हैं। बिल्कुल अपने यूट्यूब की तरह। वीडियो फ़ाइलों को विभिन्न दृश्य स्वरूपों में रखें जैसे - थंबनेल या निर्देशिका।
ऑडियो प्रकार के साथ भी ऐसा ही है।
इसलिए किसी संगठन, भूमिका और उपयोगकर्ता के लिए विशिष्ट सामग्री बनाएं।
टूल से एपीआई और आपके मूडल एलएमएस से जेनरेट किए गए टोकन के साथ, आप स्ट्रीमिंग एप्लिकेशन और एलएमएस को दो तरीकों से सिंक कर सकते हैं। इस तरह, आप अपने मूडल एलएमएस को टूल के साथ और टूल को अपने एलएमएस के साथ सिंक करते हैं।
ऑन-प्रिमाइसेस या क्लाउड पर टूल का उपयोग करें।
इस टूल के शीर्ष लाभों में से एक वीडियो लाते समय आपके ब्राउज़र पर लोड को कम करना है। इसमें एक रिपॉजिटरी है जहां से आप स्ट्रीमिंग के लिए फाइल अपलोड कर सकते हैं। और सामग्री सुपर सुरक्षित है क्योंकि इसमें डेटा साझा करने की कोई गुंजाइश नहीं है।	';
$string['modulename_link'] = '	मॉड / स्ट्रीम / व्यू	';
$string['modulenameplural'] = '	धारा	';
$string['page-mod-url-x'] = '	कोई भी यूआरएल मॉड्यूल पेज	';
$string['parameterinfo'] = '	और पैरामीटर = चर=	';
$string['parametersheader'] = '	यूआरएल चर	';
$string['parametersheader_help'] = '	कुछ आंतरिक मूडल चर स्वचालित रूप से URL में जोड़े जा सकते हैं। प्रत्येक टेक्स्ट बॉक्स में पैरामीटर के लिए अपना नाम टाइप करें और फिर आवश्यक मिलान चर का चयन करें।	';
$string['pluginadministration'] = '	यूआरएल मॉड्यूल प्रशासन	';
$string['pluginname'] = '	धारा	';
$string['popupheight'] = '	पॉप-अप ऊंचाई (पिक्सेल में)	';
$string['popupheightexplain'] = '	पॉपअप विंडो की डिफ़ॉल्ट ऊंचाई निर्दिष्ट करता है।	';
$string['popupwidth'] = '	पॉप-अप चौड़ाई (पिक्सेल में)	';
$string['popupwidthexplain'] = '	पॉपअप विंडो की डिफ़ॉल्ट चौड़ाई निर्दिष्ट करता है।	';
$string['printintro'] = '	URL विवरण प्रदर्शित करें	';
$string['printintroexplain'] = '	सामग्री के नीचे URL विवरण प्रदर्शित करें? कुछ प्रदर्शन प्रकार सक्षम होने पर भी विवरण प्रदर्शित नहीं कर सकते हैं।	';
$string['rolesinparams'] = '	पैरामीटर में भूमिका के नाम शामिल करें	';
$string['search:activity'] = '	धारा	';
$string['serverurl'] = '	सर्वर यूआरएल	';
$string['zatuk:addinstance'] = '	एक नया URL संसाधन जोड़ें	';
$string['zatuk:view'] = '	यूआरएल देखें	';
$string['zatuk:canrate'] = 'दर';
$string['zatuk:create'] = 'बनाएं';
$string['zatuk:deletevideos'] = 'वीडियो हटाएँ';
$string['zatuk:viewallvideos'] = 'सभी वीडियो देखें';
$string['zatuk:viewreports'] = 'रिपोर्ट देखें';
$string['zatuk:viewuploadedvideos'] = 'अपलोड किए गए वीडियो देखें';
$string['zatuk:deletevideo'] = 'वीडियो हटाएँ';
$string['zatuk:editingteacher'] = 'संपादन अध्यापक';
$string['zatuk:editvideo'] = 'वीडियो संपादित करें';
$string['zatuk:manageactions'] = 'क्रियाएँ प्रबंधित करें';
$string['zatuk:myaddinstance'] = 'उदाहरण जोड़ें';
$string['zatuk:uploadvideo'] = 'विडियो को अॅॅपलोड करें';
$string['zatuk:viewuploadedvideo'] = 'View Uploaded Video';
$string['zatuk:viewvideos'] = 'View Videos';
$string['width'] = '	चौड़ाई	';
$string['height'] = '	ऊंचाई	';
$string['zatukanalyticsuser'] = '	स्ट्रीम एनालिटिक्स उपयोगकर्ता	';
$string['zatukanalyticsemail'] = '	स्ट्रीम एनालिटिक्स उपयोगकर्ता ईमेल	';
$string['appearence'] = '	दिखावट	';
$string['specificstar'] = '
{$a}
सितारा	';
$string['postcomment'] = '	समीक्षा पोस्ट करें	';
$string['reviews'] = '	समीक्षा	';
$string['reviews_for'] = '	के लिए समीक्षाएं "
{$a}
"	';
$string['writereview'] = '	एक समीक्षा लिखे!	';
$string['enable_reviews'] = '	समीक्षा सक्षम करें	';
$string['configlocal_review_help'] = '	मॉड्यूल पर समीक्षा सक्षम करें	';
$string['report'] = '	रिपोर्ट ';
$string['reports'] = '	रिपोर्टों	';
$string['zatukreports'] = '	स्ट्रीम रिपोर्ट	';
$string['topviews'] = '	सबसे ज्यादा देखा गया	';

$string['fivemins'] = 'Most liked ( > 5 minutes)';
$string['fivetotenmins'] = 'Most liked ( > 5 minutes AND < 10 minutes)';
$string['abovetenmins'] = 'Most liked ( > 10 minutes)';
$string['activevideos'] = 'Active/Total Videos';

$string['streamedvideos'] = '	स्ट्रीम किए गए मिनट	';
$string['totalviews'] = '	कुल दृश्य	';
$string['uploadedvideos'] = '	वीडियो	';
$string['uploadvideo'] = '	विडियो को अॅॅपलोड करें	';
$string['organization'] = '	संगठन	';
$string['title'] = '	शीर्षक	';
$string['tags'] = '	टैग	';
$string['videodescription'] = '	विवरण	';
$string['titlerequired'] = '	अपेक्षित	';
$string['filepath'] = '	वीडियो	';
$string['filepathrequired'] = '	वीडियो आवश्यक	';
$string['thumbnail'] = '	थंबनेल	';
$string['advancedfields'] = 'Advanced Fields';

$string['standard'] = 'मानक';
$string['url'] = 'url';

$string['views'] = 'विचारों	';
$string['picture'] = '	का चित्र	';
$string['user'] = '	उपयोगकर्ता	';
$string['email'] = '	ईमेल	';
$string['rated'] = '	रेटेड	';
$string['lastviewedon'] = '	अंतिम बार देखा गया	';
$string['view'] = '	राय	';
$string['lastviewed'] = '	अंतिम बार देखा गया	';
$string['video'] = '	वीडियो	';
$string['date'] = '	तारीख	';
$string['table'] = '	टेबल	';
$string['videoname'] = '	वीडियो का नाम	';
$string['browsevideo'] = '	वीडियो ब्राउज़ करें	';
$string['required'] = '	अपेक्षित	';
$string['selectvideo'] = '	वीडियो चुनें	';
$string['week'] = '	सप्ताह	';
$string['month'] = '	महीना	';
$string['year'] = '	साल	';
$string['custom'] = '	रिवाज	';
$string['all'] = '	सब	';
$string['startdateenddate'] = '	प्रारंभ तिथि - समाप्ति तिथि	';
$string['filter'] = '	फ़िल्टर	';
$string['activities'] = '	गतिविधियां:	';
$string['videossummary'] = '	वीडियो सारांश	';
$string['course'] = '	कोर्स	';
$string['averagetime'] = '	औसत समय	';
$string['uploadedon'] = '	पर अपलोड किया गया	';
$string['uploadedby'] = '	द्वारा डाली गई	';
$string['graph'] = '	ग्राफ़';
$string['reporttable'] = 'रिपोर्ट तालिका	';
$string['activitystatus'] = '	गतिविधि की स्थिति	';
$string['status'] = '	स्थिति	';
$string['startedon'] = '	को प्रारंभ करें	';
$string['completedon'] = '	पर पूर्ण	';
$string['timeperiod'] = '	समय सीमा	';
$string['day'] = '	दिन	';
$string['completedvideos'] = '	पूर्ण वीडियो	';
$string['studentparticipation'] = '	छात्र भागीदारी	';
$string['coursestats'] = '	पाठ्यक्रम आँकड़े	';
$string['totalvideos'] = '	कुल वीडियो	';
$string['activevideos'] = '	सक्रिय वीडियो	';
$string['videotrends'] = '	वीडियो रुझान	';
$string['#ofattempts'] = '	#प्रयासों का';

$string['bar'] = 'बार-';
$string['na'] = '	एन/ए	';
$string['byactivity'] = '	गतिविधि द्वारा	';
$string['selected'] = '	चयनित	';
$string['manager'] = '	मैनेजर	';
$string['bycourse'] = '	बेशक	';
$string['querywrong'] = '	एसक्यूएल क्वेरी गलत!';

// Local/zatuk.
$string['disabled'] = 'disabled="disabled"';
$string['completed'] = '	पूरा हुआ	';
$string['notyetstarted'] = '	अभी तक शुरू नहीं किया	';
$string['inprogress'] = '	चालू	';
$string['notsynced'] = '	सिंक नहीं किया गया	';
$string['syncedat'] = '	पर समन्वयित किया गया	';
$string['noreport'] = '	रिपोर्ट मौजूद नहीं है	';
$string['noofviewsbyuser'] = '	उपयोगकर्ता द्वारा देखे जाने की संख्या	';
$string['dailyhitsviews'] = '	दैनिक हिट्स/दृश्य	';
$string['hitsviews'] = '	हिट/दृश्यView	';
$string['noofusers'] = '	उपयोगकर्ता की संख्या	';
$string['tablesearch'] = '	खोज...	';
$string['eventzatukactivityviewed'] = '	स्ट्रीम गतिविधि देखी गई	';
$string['eventvideoplayed'] = '	वीडियो चलाया गया	';
$string['eventvideorated'] = '	रेटिंग दी गई	';
$string['eventvideocompleted'] = '	वीडियो पूरा हुआ	';
$string['eventvideopaused'] = '	वीडियो रोका गया	';
$string['zatukingapp'] = '	स्ट्रीम ऐप	';
$string['recordsession'] = '	रिकॉर्ड सत्र';
$string['nozatukrepository'] = 'कृपया स्ट्रीम रिपॉजिटरी को इसमें सक्षम करें enable <u><a href="{$a}">continue</a></u>';
$string['enableanalyticsdesc'] = 'डिफ़ॉल्ट रूप से यह सक्षम हो जाएगा.';
