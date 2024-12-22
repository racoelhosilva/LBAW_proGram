import "./bootstrap";
import {addCommentSectionListeners, addPostListeners, addSubmitCommentListener} from "./post.js";
import {addSearchListeners} from "./search.js";
import {addHomeEventListeners} from "./home.js";
import {addCommentListeners} from "./comment.js";
import {addOAuthButtonListeners, addToggleViewPasswordVisibilityListener} from "./auth.js";
import {addBanModalListeners} from "./admin.js";
import {addFaqListeners} from "./faq.js";
import {addGroupPostsListeners, toggleGroupChatAndAnnouncements} from "./group.js";
import {addHeaderListeners} from "./header.js";
import {addSendInviteListeners, addUnsendInviteListeners} from "./manage-group-invites.js";
import {removeMemberListener} from "./manage-group-members.js";
import {acceptRequestListener, rejectRequestListener} from "./manage-group-requests.js";
import {addNotificationListeners} from "./notifications.js";
import {addHandleRequestListeners, addRemoveFollowerListeners, addUserPostsListeners} from "./user.js";
import {addLeaveGroupListeners} from "./user-groups.js";
import {addInviteAcceptListeners, addInviteRejectListeners} from "./user-invites.js";
import {
	activateQuill,
	addCopyButtonListeners,
	addResponsiveDropdownListeners,
	addToastMessageListeners,
	addSelectListeners,
	addModalListeners,
	addDropdownListeners
} from "./component.js";


const addAllListeners = () => {
	addDropdownListeners();
	addModalListeners();
	addToastMessageListeners();
	activateQuill();
	addSelectListeners();
	addResponsiveDropdownListeners();
	addCopyButtonListeners();

	addHomeEventListeners();
	addSubmitCommentListener();
	addPostListeners();
	addCommentListeners();
	addCommentSectionListeners();
	addSearchListeners();

	addBanModalListeners();
	addToggleViewPasswordVisibilityListener();
	addOAuthButtonListeners();
	addFaqListeners();
	toggleGroupChatAndAnnouncements();
	addGroupPostsListeners();
	addHeaderListeners();
	addSendInviteListeners();
	addUnsendInviteListeners();
	removeMemberListener();
	acceptRequestListener();
	rejectRequestListener();
	addNotificationListeners();
	addRemoveFollowerListeners();
	addHandleRequestListeners();
	addUserPostsListeners();
	addLeaveGroupListeners();
	addInviteRejectListeners();
	addInviteAcceptListeners();
}

addAllListeners();

export { addToastMessageListeners, addSelectListeners };
