import './bootstrap';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.interactionPlugin = interactionPlugin;
window.timeGridPlugin = timeGridPlugin;
