<?php
/**
* @package Language
* @author Tim Rupp <tarupp01@indianatech.net>
* @copyright GPL
*/

/**
* Copyright (C) 2004-2005 Tim Rupp
* Please direct all questions and comments to TARupp01@indianatech.net
*
* This program is free software; you can redistribute it and/or modify it under the terms of
* the GNU General Public License as published by the Free Software Foundation; either version
* 2 of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along with this program;
* if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston,
* MA 02111-1307, USA.
*/

/**
* Prevent direct access to the file
*/
defined( '_FLARE_INC' ) or die( "You can't access this file directly." );

/* Groups page specific texts */
define ("_GROUPS_WELCOME", "My Groups on F.U.E.L");
define ("_GROUPS_CREATE_WELCOME", "Create a New Collaborative Group");
define ("_GROUPS_INVITE_WELCOME", "Invite a User To Your Group");
define ("_GROUPS_EDIT_WELCOME", "Change Your Collaborative Group");
define ("_GROUPS_IN", "Groups That I Am a Member Of");
define ("_GROUPS_MINE", "Groups That I Have Created");
define ("_GROUPS_INVITES_MINE", "My Invites on F.U.E.L");
define ("_GROUPS_INVITES", "Invites Received From Others");
define ("_GROUPS_INVITES_SENT", "Invites That I Have Sent To Others");
define ("_GROUPS_INVITES_CREATE_SUCCESS", "Successfully sent invitations to selected user(s) to join your group!");
define ("_GROUPS_INVITES_CREATE_FAILURE", "Failed to send invitations to selected user(s)!");
define ("_GROUPS_INVITES_CREATE_RETURN_LINK", "<a href='index.php?extension=Groups&amp;action=show_groups'>Return to My Groups</a>");
define ("_GROUPS_CREATE_SUCCESS", "Successfully created new collaborative group!");
define ("_GROUPS_CREATE_FAILURE", "Failed to create new collaborative group!");
define ("_GROUPS_CREATE_RETURN_LINK", "<a href='index.php?extension=Groups&amp;action=show_groups'>Return to My Groups</a>");
define ("_GROUPS_DELETE_GROUPS", "Delete Groups");
define ("_GROUPS_EDIT_GROUPS", "Edit Groups");
define ("_GROUPS_LEAVE_GROUPS", "Leave Group(s)");
define ("_GROUPS_RETRACT_INVITE", "Retract Invite");
define ("_GROUPS_ACCEPT_INVITE", "Accept Invitation");
define ("_GROUPS_ACCEPT_INVITE_SUCCESS", "Accepted selected invite(s)<p>You've joined the group! Your groups folder has been updated!");
define ("_GROUPS_ACCEPT_INVITE_FAILURE_NONE", "You must select at least one invite to accept!");
define ("_GROUPS_ACCEPT_INVITE_FAILURE_ALREADY_MEMBER", "Could not add you to the following group(s) because it seems you are already a member of them");
define ("_GROUPS_ACCEPT_INVITE_FAILURE_LINKS_BROKE", "Linking to the below links failed! You weren't able to join the groups below");
define ("_GROUPS_ACCEPT_INVITE_RETURN_LINK", "<a href='index.php?extension=Groups&amp;action=show_groups'>Return to My Groups</a>");
define ("_GROUPS_DECLINE_INVITE", "Decline Invitation");
define ("_GROUPS_DECLINE_INVITE_SUCCESS", "Declined selected invite(s)<p>The person who invited you has been notified.");
define ("_GROUPS_DECLINE_INVITE_FAILURE", "Failed declining selected invite(s).");
define ("_GROUPS_DECLINE_INVITE_RETURN_LINK", "<a href='index.php?extension=Groups&amp;action=show_groups'>Return to My Groups</a>");
define ("_GROUPS_DELETE_SUCCESS", "Successfully removed collaborative group(s)<p>Also, all group members have been removed from the group.");
define ("_GROUPS_DELETE_FAILURE", "Failed to remove collaborative group(s)");
define ("_GROUPS_DELETE_RETURN_LINK", "<a href='index.php?extension=Groups&amp;action=show_groups'>Return to My Groups</a>");
define ("_GROUPS_ADM_DELETE_RETURN_LINK", "<a href='admin.php?extension=Groups&amp;action=show_groups'>Return to My Groups</a>");
define ("_GROUPS_EDIT_SUCCESS", "Successfully updated collaborative group!");
define ("_GROUPS_EDIT_FAILURE", "Failed to successfully update the collaborative group!");
define ("_GROUPS_EDIT_RETURN_LINK", "<a href='index.php?extension=Groups&amp;action=show_groups'>Return to My Groups</a>");
define ("_GROUPS_LEAVE_SUCCESS", "Successfully left the collaborative group(s)");
define ("_GROUPS_LEAVE_FAILURE", "Failed to leave the collaborative group(s)");
define ("_GROUPS_LEAVE_RETURN_LINK", "<a href='index.php?extension=Groups&amp;action=show_groups'>Return to My Groups</a>");
define ("_GROUPS_CHECK_EMPTY", "You must enter a group name before we can check if it's available!");
define ("_GROUPS_CHECK_EMPTY_RETURN_LINK", "<a href='#' onClick='javascript:window.close()'>Close Window</a>");
define ("_GROUPS_CHECK_EXISTS", "The group being created already exists.");
define ("_GROUPS_CHECK_NO_EXISTS", "The group being created does not already exist.");
define ("_GROUPS_LIST", "List Groups");
define ("_GROUPS_CREATE", "Create Group");
define ("_GROUPS_INVITE_USER", "Invite User");
define ("_GROUPS_GROUP_NAME", "Group Name");
define ("_GROUPS_INITIAL_MEMBERS", "Initial Group Members");
define ("_GROUPS_INITIAL_MEMBERS_FYI", "( Hold down the Ctrl key to select more than 1)"
    . "<p />"
    . "Note that as the group admin, you are automatically<br />"
    . "included in the member list");
define ("_GROUPS_CURRENT_MEMBERS", "Remove Group Members");
define ("_GROUPS_CURRENT_MEMBERS_FYI", "( If you wish to remove members from this group, select them here )"
    . "<p />"
    . "More than one member can be selected by holding down the Ctrl key.");
define ("_GROUPS_CHECK_AVAIL", "Check Availability");
define ("_GROUPS_NEW_GROUP", "Create New Group");
define ("_GROUPS_NO_GROUPS_ADMIN", "You do not admin any groups");
define ("_GROUPS_MEMBERS_ADD", "Members to Add to Group");
define ("_GROUPS_MEMBERS_ADD_FYI", "Hold down the Ctrl key to select more than 1");
define ("_GROUPS_SEND_INVITE", "Send Invites");
define ("_GROUPS_RECEIVED_INVITE_FROM", "Received invitation from");
define ("_GROUPS_TO_JOIN", "to join the group");
define ("_GROUPS_ADMIN", "Group Admin");
define ("_GROUPS_ADM_SHOW_ALL_GROUPS", "Show All Groups");
define ("_GROUPS_CREATION_DATE", "Creation Date");
define ("_GROUPS_QUOTA_TOTAL", "Quota (Total)");
define ("_GROUPS_QUOTA_FREE", "Quota (Free)");
define ("_GROUPS_NUM_MEMBERS", "Number of Members");
define ("_GROUPS_NO_GROUPS_EXIST", "No groups currently exist");
define ("_GROUPS_CUR_SHARE_AMNT", "Current Share Amount");
define ("_GROUPS_SHARE_AMNT", "Share Amount");
define ("_GROUPS_SHARE_AMNT_MESG", "You can change this if you want to share more.");
define ("_GROUPS_SHARE_AMNT_SUB", "This amount will be subtracted from<br />your total storage amount");
define ("_GROUPS_SELECT_GROUP_EDIT", "You must select a group to edit!");
define ("_GROUPS_SELECT_GROUP_EDIT_RETURN_LINK", "<a href='index.php?extension=Groups'>Return to My Groups</a>");
define ("_GROUPS_CHANGE_GROUPS", "Change Groups");
define ("_GROUPS_TYPE", "Group Type");
define ("_GROUPS_TRUSTED", "Trusted");
define ("_GROUPS_DISTRO", "Distribution");
define ("_GROUPS_TRUSTED_NFO", "In a trusted group, all group members can write to the group folder, and everyone can read from the group folder.");
define ("_GROUPS_DISTRO_NFO", "In a distribution group, only the owner of the group can write to the folder. "
    . "Everyone else can only read from the folder.");
define ("_GROUPS_CHARS_LEFT", "64 characters left.");
define ("_GROUPS_GROUPDIR", "Group Directory");
define ("_GROUPS_REQUEST_JOIN", "Request to Join a Group");
define ("_GROUPS_RETRACT_REQUEST", "Retract Request to Join Group");
define ("_GROUPS_REQUEST_SUCCESS", "Sucessfully sent request");
define ("_GROUPS_REQUEST_RETURN_LINK", "<a href='index.php?extension=Groups&amp;action=show_request_join'>Return to request another group'</a>");
define ("_GROUPS_REQUEST_MUST_SELECT_ONE", "You must select at least one group to send a request to");
define ("_GROUPS_INVITE_MUST_SELECT_ONE", "Must select at least one invite to retract");
define ("_GROUPS_INVITE_RETURN_LINK", "<a href='index.php?extension=Groups'>Return to My Groups</a>");
define ("_GROUPS_RETRACT_REQUEST_SUCCESS", "Sucessfully retracted the selected requests for group membership");
define ("_GROUPS_RETRACT_REQUEST_RETURN_LINK", "<a href='index.php?extension=Groups'>Return to My Groups</a>");
define ("_GROUPS_DENY_REQUEST", "Deny requests");
define ("_GROUPS_ALLOW_REQUEST", "Allow requests");
define ("_GROUPS_GENERAL_RETURN_LINK", "<a href='index.php?extension=Groups'>Return to My Groups</a>");

?>
