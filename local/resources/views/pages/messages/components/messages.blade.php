<div class="p-4 card" style="border-radius: 15px;">
    @if (count($messages) == 0)
        <div class="text-center">
            <i class="mdi mdi-inbox-multiple mdi-36px"></i>
            <br><br>
            No Messages
        </div>
    @else
        <div id="scrollable" class="scrollable-container">
            @foreach ($messages as $item)
                @if (Auth::user()->id == $item->sender)
                    <div class="text-end">
                        <p style="font-size: 10px; margin-bottom: 0px;">Me</p>
                        <div class="item text-end" style="color: white; background-color: green; border-radius: 20px 10px 0px 20px; width: 100%; ">
                            <p>{{ $item->message }}</p>
                        </div>
                        <p style="font-size: 10px;">{{ $item->time }}</p>
                    </div>
                @else
                    <div class="text-start">
                        <p style="font-size: 10px; margin-bottom: 0px;">{{ $item->sender_name }}</p>
                        <div class="item text-start" style="border-radius: 10px 20px 20px 0px; width: 80%;">
                            <p>{{ $item->message }}</p>
                        </div>
                        <p style="font-size: 10px;">{{ $item->time }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    <hr>
    @if ($viewOnly)
        <center>Access: view only</center>
    @else
        <textarea class="p-3" name="message" id="message" rows="5" style="border: solid 1px gray; border-radius: 10px;" placeholder="Enter your message here"></textarea>
        <input type="hidden" id="group_reference" value="{{ $group_reference }}">
        <div class="mt-3 text-end">
            <button class="btn btn-primary" onclick="handleSendMessage()">Send</button>
        </div>
    @endif
</div>