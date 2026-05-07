Nueva reserva.<br><br>

👤 Nombre: {{ $reservation->customer_name }}<br>
📅 Fecha y hora: {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}<br>
🔢 Número de reserva: {{ $reservation->party_size }}<br>
📞 Teléfono: {{ $reservation->customer_phone }}<br>
📧 Correo: {{ $reservation->customer_email ?? 'N/A' }}<br>
Mensaje: {{ $reservation->notes ?? 'Sin mensaje' }}<br><br>

<a href="https://samplerestaurant.test/">Sample Restaurant</a>
