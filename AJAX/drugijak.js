var allGalleriesData;
var currentGalleryId;

$.get("https://jsonplaceholder.typicode.com/albums", function (data) {
   var galleryList = $("#gallery-list");

   data.forEach(function (album) {

      var listItem = $("<li>").addClass("gallery-list-item");
      var title = $("<p>").addClass("gallery-title").text(album.title);
      listItem.attr("data-album-id", album.id);
      listItem.append(title);
      galleryList.append(listItem);
   });
   allGalleriesData = data;
});
$(document).on("click", ".gallery-list-item", function () {
   currentGalleryId = $(this).data("album-id");

   $.get("https://jsonplaceholder.typicode.com/albums/" + currentGalleryId, function (albumData) {
      var galleryTitle = $("#gallery-title");
      var galleryDescription = $("#gallery-description");
      var galleryThumbnails = $("#gallery-thumbnails");
      galleryTitle.empty();
      galleryDescription.empty();
      galleryThumbnails.empty();
      galleryTitle.text(albumData.title);

      var description = $("<p>").addClass("gallery-description").text("Opis galerii: " + albumData.id);
      galleryDescription.append(description);

      $.get("https://jsonplaceholder.typicode.com/photos?albumId=" + currentGalleryId, function (photoData) {
         photoData.forEach(function (photo) {
            var thumbnail = $("<a>").attr("href", photo.url).attr("data-lightbox", "gallery");
            var image = $("<img>").addClass("thumbnail-image").attr("src", photo.thumbnailUrl);
            thumbnail.append(image);
            galleryThumbnails.append(thumbnail);
         });
      });
      $(".gallery-list-item").not(this).hide();
      $("#gallery-details").show();
      $("#show-all-button").show();
   });
});

$("#show-all-button").click(function () {
   var galleryList = $("#gallery-list");

   galleryList.empty();

   allGalleriesData.forEach(function (album) {
      var listItem = $("<li>").addClass("gallery-list-item");
      var title = $("<p>").addClass("gallery-title").text(album.title);
      listItem.attr("data-album-id", album.id);
      listItem.append(title);
      galleryList.append(listItem);
   });
   $(".gallery-list-item").show();
   $("#gallery-details").hide();
   $("#other-section").hide();
   $(this).hide();
});

function openForm() {
   $("#image-form").show();
}

$("#add-image-form").submit(function (event) {
   event.preventDefault();

   var title = $("#image-title").val();
   var url = $("#image-url").val();

   var imageData = {
      title: title,
      url: url,
      albumId: currentGalleryId
   };

   $.ajax({
      url: "https://jsonplaceholder.typicode.com/photos",
      type: "POST",
      data: imageData,
      success: function (response) {
         alert("Obrazek został dodany!");

         $("#image-title").val("");
         $("#image-url").val("");
      },
      error: function () {
         alert("Wystąpił błąd podczas dodawania obrazka.");
      }
   });
});
