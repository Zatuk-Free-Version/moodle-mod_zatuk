# Zatuk [Version 1]

    Maintained by: Naveen, Ranga Reddy
    Copyright: Moodle India
    License: http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

    Welcome to the README for the zatuk plugin in Moodle! This document provides information about the zatuk plugin, how to install and use it, and customization options.

# Description:

    The Zatuk Video Integration Plugin represents a pivotal addition to the Moodle platform, designed to enrich the educational experience by seamlessly integrating synchronized video functionalities. This plugin serves as a versatile activity module within Moodle, empowering educators and learners alike with advanced video management capabilities.

    Synchronized Video Showcasing:
    The plugin enables the display of synchronized videos that are uploaded from the Zatuk server directly onto Moodle. This feature ensures that educational content, lectures, and presentations are effortlessly accessible within the Moodle environment.

    Detailed Reports:
    Gain valuable insights into video performance with comprehensive reports. Track metrics such as ratings, likes, dislikes, and view counts directly within Moodle. These analytics empower educators to gauge learner engagement and optimize content delivery based on real-time feedback.

    Seamless Video Upload and Sync:
    With the Zatuk Video Sync Plugin, uploading videos from Moodle to the streaming server is streamlined and efficient. This process not only simplifies content management but also reduces server load by leveraging the robust capabilities of the Zatuk server infrastructure.

    Enhanced Server Performance:
    Offloading video streaming to the dedicated Zatuk server enhances server performance within Moodle. By utilizing Zatuk's optimized streaming capabilities, administrators can ensure smooth video playback without compromising on system resources, thereby improving overall platform stability.

# Short Description:

  The Zatuk Video Integration Plugin enhances Moodle by providing advanced video management features. It allows seamless integration of synchronized videos from the Zatuk server, offers 
  detailed performance reports (e.g., views, likes), and streamlines video upload and syncing. By offloading streaming to Zatuk’s server, it improves Moodle’s performance and stability.

# Installation:

    1. Click on Site Administration from the navigation block.
    2. Select the Plugins tab from the list of tabs.
    3. Click on Install Plugin from the options. The page is redirected to the plugin installer.
    4. The user can install the plugin using the Choose File option, or he or she can drag and drop the downloaded zip file into the drag and drop box.
    5. After choosing the file, click on Continue until the upgrade to the new version is successful.
    6. On installation, Go to Manage Repositories in the site administration, enable the zatuk plugin, and click on save button, which will generate the token for zatuk webservices and secret, key in streaming server by creating the organisation.

# Requirements:
    Based on moodle version user need to install the compatiable zatuk plugin.
    
     1. Navigate to the specific course in the LMS.
     2. Click on "More Options" to reveal additional features.
     3. Ensure that "Zatuk" is visible in the more options menu.
     4. Allow admin or teacher to upload videos from the LMS.
     5. Videos will be published once the cron job runs successfully.
     6. Upon successful execution, videos will be moved to the Zatuk application.
     7. Once published in the Zatuk application, videos will be ready for playback.
     8. Users can click on the video to start playing it.
    9. The video will be displayed in the Zatuk activity when added to the course.

    This streamlined process ensures that users can easily access and view videos, enhancing their learning experience through seamless integration and immediate playback 
    within the course framework.

# How to install:

    1. Click on Site Administration from the navigation block.
    2. Select the Plugins tab from the list of tabs.
    3. Click on Install Plugin from the options. The page is redirected plugin installer.
    4. User can install the Plugin by Choose File option or he/she can drag and drop the downloaded zip file in the drag and drop box.
    5. After choosing the file click on continue till the Upgrade of the new version is successful.

# Supported Moodle versions:
    Moodle 4.2

# Code repository name:
    Moodle-mod_zatuk

# Dependencies:
    Moodle-repository_zatuk

# Cross-DB compatibility:
    Compatible with PGSQL, MSSQL, MYSQL and MariaDB


# Documentation URL:
    https://zatuk.com/knowledge-base/
