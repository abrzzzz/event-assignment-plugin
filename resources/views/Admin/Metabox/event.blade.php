
<div id="event-meta-box">
    <div>
        <label for="even-date">Event Date</label>
        <input type="date" id="even-date" name="event_meta[date]" value={{isset($event_meta['date']) ?  $event_meta['date'] : ''}}>
    </div>
    <div>
        <label for="even-location">Event Date</label>
        <input type="location" name="event_meta[location]" placeholder="type the location" value={{ isset($event_meta['location']) ?  $event_meta['location'] : '' }}>
    </div>
</div>

<style>
    #event-meta-box{
        padding: 20px;
        display: flex;
        gap: 10px;
        justify-content: space-around;
        align-items: center
    }
    #event-meta-box input{
        padding: 5px 10px;
        border-radius: 7px;
        border: 1px solid #ddd;
    }
</style>