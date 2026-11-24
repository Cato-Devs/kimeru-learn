{{--
    The fields shared by the "add" and "edit" forms.
    old('kimeru', $word->kimeru) shows what was typed if validation failed,
    otherwise the saved value (empty for a new word).
--}}
<div class="space-y-5">
    <div>
        <label for="kimeru" class="field-label">Kimeru</label>
        <input id="kimeru" name="kimeru" type="text" required maxlength="100"
               value="{{ old('kimeru', $word->kimeru) }}"
               @error('kimeru') aria-invalid="true" aria-describedby="kimeru-error" @enderror
               class="field">
        @error('kimeru')
            <p id="kimeru-error" class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="english" class="field-label">English meaning</label>
        <input id="english" name="english" type="text" required maxlength="150"
               value="{{ old('english', $word->english) }}"
               @error('english') aria-invalid="true" aria-describedby="english-error" @enderror
               class="field">
        @error('english')
            <p id="english-error" class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="notes" class="field-label">Notes <span class="font-normal text-ink/60">(optional: dialect, pronunciation, source)</span></label>
        <textarea id="notes" name="notes" rows="3" maxlength="500"
                  @error('notes') aria-invalid="true" aria-describedby="notes-error" @enderror
                  class="field">{{ old('notes', $word->notes) }}</textarea>
        @error('notes')
            <p id="notes-error" class="field-error">{{ $message }}</p>
        @enderror
    </div>
</div>
