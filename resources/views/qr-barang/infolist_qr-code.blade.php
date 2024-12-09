<div>
    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate($getState())) !!} ">
</div>