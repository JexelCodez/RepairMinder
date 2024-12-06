<div>
    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(50)->generate($getState())) !!} ">
</div>
