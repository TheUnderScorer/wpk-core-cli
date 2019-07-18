export default interface CoreVars
{
    ajaxUrl: string;
    messages: Messages;
}

export interface Messages
{
    orderPlaced: string;
    feedbackSent: string;
    requiredField: string;
    reviewSaved: string;
    eventJoined: string;
    eventLeft: string;
    eventAccepted: string;
    eventRejected: string;
    permissionsUpdated: string;
    organigramUpdated: string;
    newEmployeeAdded: string;
    attendanceAdded: string;
    attendanceAddingError: string;
    attendanceRemoved: string;
    cantMoveSection: string;
    taskAdded: string;
    trackAdded: string;
    settingSaved: string;
}
