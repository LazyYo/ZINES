<div class="flex-wrapper background-img">
    <form action="forms/submit_example" method="post" class="dark-bg form-ajax" ajax-success="console.log" ajax-failure="console.error">
        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">perm_identity</i>
                <input placeholder="Username" type="text" name="username">
            </div>
        </div>

        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">lock_outline</i>
                <input placeholder="Password" type="password" name="password">
            </div>
        </div>

        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">add</i>
                <input placeholder="The number you like the most" type="number" name="number">
            </div>
        </div>

        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">format_quote</i>
                <textarea class="input" placeholder="A citation you like" name="quote"></textarea>
            </div>
        </div>

        <div class="input-wrapper">
            <div class="input">
                <i class="icon material-icons">format_quote</i>
                <select class="input" placeholder="A citation you like" name="select">
                    <option value="Descartes">Cogito ergo sum.</option>
                    <option value="Churchill">Blabla.</option>
                </select>
            </div>
        </div>


        <button hidden type="submit">Submit</button>
    </form>
</div>
