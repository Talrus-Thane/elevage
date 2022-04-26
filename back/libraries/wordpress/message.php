<?php

function acym_enqueueMessage($message, $type = 'success', $addNotification = true)
{
    $type = str_replace(['notice', 'message'], ['info', 'success'], $type);
    $message = is_array($message) ? implode('<br/>', $message) : $message;

    $handledTypes = ['info', 'warning', 'error', 'success'];

    if (in_array($type, $handledTypes)) {
        acym_session();
        if (empty($_SESSION['acymessage'.$type]) || !in_array($message, $_SESSION['acymessage'.$type])) {
            $_SESSION['acymessage'.$type][] = $message;
        }
    }

    return true;
}

function acym_displayMessages()
{
    $types = ['success', 'info', 'warning', 'error'];
    acym_session();
    foreach ($types as $type) {
        if (empty($_SESSION['acymessage'.$type])) continue;

        acym_display($_SESSION['acymessage'.$type], $type);
        unset($_SESSION['acymessage'.$type]);
    }
}
