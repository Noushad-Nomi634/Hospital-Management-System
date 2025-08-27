<h3>Edit Availability for {{ $availability->doctor->name ?? 'Doctor' }}</h3>

<form action="{{ route('doctors.availability.update', $availability->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Date:</label>
    <input type="date" name="date" value="{{ $availability->date }}" required><br>

    <label>Start Time:</label>
    <input type="time" name="start_time" value="{{ $availability->start_time }}" required><br>

    <label>End Time:</label>
    <input type="time" name="end_time" value="{{ $availability->end_time }}" required><br>

    <button type="submit">Update Slot</button>
</form>
