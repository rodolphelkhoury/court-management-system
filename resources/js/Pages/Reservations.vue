<script setup>
import { ScheduleXCalendar } from '@schedule-x/vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
  createCalendar,
  createViewDay,
  createViewMonthAgenda,
  createViewMonthGrid,
  createViewWeek,
} from '@schedule-x/calendar'
import '@schedule-x/theme-default/dist/index.css'
import { createDragAndDropPlugin } from '@schedule-x/drag-and-drop'
import { createResizePlugin } from '@schedule-x/resize'
import { Head, Link, router } from '@inertiajs/vue3';
import { createEventsServicePlugin } from '@schedule-x/events-service'

const props = defineProps({
    court: {
      type: Object,
      required: true
    },
    reservations: {
        type: Array,
        required: true
    },
});

const eventsServicePlugin = createEventsServicePlugin();
const viewWeek = createViewWeek({ cellDuration: 60 });
const viewMonthGrid = createViewMonthGrid();
const viewDay = createViewDay();

const getCurrentDate = () => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

const calendarApp = createCalendar({
  selectedDate: getCurrentDate(),
  views: [
    viewDay,
    viewWeek,
    viewMonthGrid,
    createViewMonthAgenda(),
  ],
  defaultView: viewWeek.name,
  events: [],
  callbacks: {
    onEventUpdate(event) {
      updateEvent(event);
    },
    onEventClick(event) {
      clickOnEvent(event);
    }
  },
}, [
  createDragAndDropPlugin(),
  createResizePlugin(),
  eventsServicePlugin,
]);

const transformReservationsToScheduleEvents = (reservations) => {
  return reservations.map((reservation) => {
    const formatTime = (time) => time.substring(0, 5);

    return {
      id: reservation.id,
      title: `Reservation for ${reservation.customer.name}`,
      start: `${reservation.reservation_date} ${formatTime(reservation.start_time)}`,
      end: `${reservation.reservation_date} ${formatTime(reservation.end_time)}`,
      customer_id: reservation.customer.id,
      section_id: reservation.section_id,
      reservation_date: reservation.reservation_date,
      start_time: reservation.start_time,
      end_time: reservation.end_time,
      is_canceled: reservation.is_canceled,
      court_id: reservation.court_id
    };
  });
};

const transformEventToReservation = (event) => {
  const extractTime = (datetime) => datetime.split(' ');

  return {
    id: event.id,
    customer: {
      name: event.title.replace('Reservation for ', ''),
    },
    reservation_date: extractTime(event.start)[0],
    start_time: extractTime(event.start)[1],
    end_time: extractTime(event.end)[1],
    customer_id: event.customer_id,
    section_id: event.section_id,
    is_canceled: event.is_canceled,
    court_id: event.court_id
  };
};

const addEvents = (reservations) => {
  const events = transformReservationsToScheduleEvents(reservations);
  events.forEach(event => {
    eventsServicePlugin.add(event);
  });
};

const updateEvent = (event) => {
  const updatedReservation = transformEventToReservation(event);
  axios
    .put(`/courts/${event.court_id}/reservations/${updatedReservation.id}`, updatedReservation)
    .then((response) => {
      // Optional: Provide feedback to the user
    })
    .catch((error) => {
      alert(error.response.data.message);
      const allEvents = eventsServicePlugin.getAll(); // Assuming getAll() retrieves all events
      allEvents.forEach(existingEvent => {
        eventsServicePlugin.remove(existingEvent.id);
      });
      router.reload({ only: ['reservations'] })
      addEvents(props.reservations);
    });
};

const clickOnEvent = (event) => {
  router.visit(route('get.reservation', {reservation: event.id}))
};

addEvents(props.reservations);
</script>

<template>
  <Head title="Reservations" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between">
          <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Reservations
          </h2>
          <div>
              <Link 
                  :href="`/courts/${court.id}/create-reservations`" 
                  class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                  Add
              </Link>
          </div>
      </div>
    </template>
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 dark:text-gray-200">
          <ScheduleXCalendar :calendar-app="calendarApp" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.sx-vue-calendar-wrapper {
  height: 800px;
  max-height: 90vh;
}
</style>
