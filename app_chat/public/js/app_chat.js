// alert("hello");

//sau khi có tin nhắn mới, giúp tự động cuộn xuống dưới cùng để người dùng không phải kéo xuống thủ công.
function scrollToBottom() {
    const container = document.getElementById("conversation-body");
    container.scrollTop = container.scrollHeight;
  }

// Gửi tin nhắn khi ấn enter
$("#inputMsg").keydown(function(event) {
  if (event.key === "Enter") {
      // Gọi hàm sendMail() sau khi nhấn Enter
      sendMail();
      // Ngừng hành động mặc định (ngừng tạo dòng mới khi nhấn Enter trong input)
      event.preventDefault();
  }
});