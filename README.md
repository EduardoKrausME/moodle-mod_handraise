# mod_handraise - Hand raise

Moodle activity for organising requests to speak during classroom sessions or synchronous meetings.

## How it works

- the student opens the activity and clicks **I need to speak**;
- the request enters the queue in arrival order;
- the student can see their own position and cancel the request;
- the teacher sees the queued participants in order, including the request time and waiting time;
- when the teacher clicks **Served**, that participant is removed from the queue;
- the interface refreshes automatically through AJAX without reloading the page.

## Main use cases

Hand raise is useful in live classes, tutoring sessions, webinars, workshops and other synchronous activities where several participants may request attention at the same time. It gives the teacher a simple first-in, first-out queue while keeping the student interface compact.

## Permissions

Students use the `mod/handraise:raisehand` capability to join or leave the queue. Teachers and other authorised users use `mod/handraise:managequeue` to view the named queue and mark requests as served.

## Privacy and security

Students do not receive the names of other queued users through the API; they only see their own position and the total number of people waiting. Users with `mod/handraise:managequeue` can access the named queue.

Actions use Moodle External Functions with context and capability validation. The activity also integrates with the Privacy API, course backup and restore, completion tracking, course logs and course reset.

A Portuguese version of this documentation is available in [README.pt_br.md](README.pt_br.md).
