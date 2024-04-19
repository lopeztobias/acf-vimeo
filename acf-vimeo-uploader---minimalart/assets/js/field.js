jQuery(document).ready(function ($) {
	const client_identifier = jQuery('input[name="client_identifier"]').val();
	const client_secrets = jQuery('input[name="client_secrets"]').val();
	const accessToken = jQuery('input[name="token"]').val();


	async function fetchVideos() {
		const apiUrl = 'https://api.vimeo.com/me/videos';
		try {
			const response = await fetch(apiUrl, {
				headers: {
					'Authorization': `Bearer ${accessToken}`
				}
			});
			const data = await response.json();
			return data.data; // Array de objetos de video
		} catch (error) {
			console.error('Error al obtener los videos:', error);
			return [];
		}
	}

	// Función para cargar un video a Vimeo
	async function uploadToVimeo(fileUrl) {
		const apiUrl = 'https://api.vimeo.com/me/videos';
		const formData = new FormData();
		formData.append('upload', fileUrl);

		try {
			const response = await fetch(apiUrl, {
				method: 'POST',
				headers: {
					'Authorization': `Bearer ${accessToken}`
				},
				body: formData
			});

			if (!response.ok) {
				throw new Error('Error al cargar el video a Vimeo');
			}

			const responseData = await response.json();
			return responseData.uri; // Devuelve la URI del video en Vimeo
		} catch (error) {
			console.error('Error al cargar el video a Vimeo:', error);
			throw error;
		}
	}


	jQuery(document).ready(function ($) {

		$('#upload-button').on('click', function (e) {
			e.preventDefault();
			const fileInput = $('#video-upload')[0];
			const file = fileInput.files[0]; // Obtener el archivo seleccionado

			if (file) {
				const fileUrl = URL.createObjectURL(file); // Obtener la URL del archivo seleccionado

				// Llamar a la función uploadToVimeo con la URL del archivo seleccionado
				uploadToVimeo(fileUrl)
					.then(uri => {
						console.log('El video se ha cargado correctamente en Vimeo. URI:', uri);
						// Aquí puedes realizar cualquier acción adicional, como mostrar un mensaje de éxito al usuario
					})
					.catch(error => {
						console.error('Error al cargar el video a Vimeo:', error);
						// Aquí puedes mostrar un mensaje de error al usuario o realizar cualquier otra acción necesaria
					});
			} else {
				console.error('No se ha seleccionado ningún archivo.');
			}
		});



		$('#custom-media-button').on('click', async function (e) {
			e.preventDefault();

			try {
				const videos = await fetchVideos();
				const videoItems = [];

				for (const video of videos) {
					if (video.link.includes('vimeo.com')) {
						const thumbnailUrl = video.pictures.sizes[4].link;
						const videoItem = {
							id: video.uri.replace('/videos/', ''),
							title: video.name,
							filename: video.name,
							mime: 'video/vimeo',
							url: video.link,
							type: 'video',
							icon: thumbnailUrl,
							embed: video.embed.html
						};
						videoItems.push(videoItem);
					}
				}

				const mediaFrame = wp.media({
					title: 'Selecciona vídeos de Vimeo',
					library: {
						type: 'test'
					},
					filterable: "",
					multiple: false
				});

				mediaFrame.on('open', function () {
					const attachments = mediaFrame.state().get('library');
					attachments.reset(videoItems);

					// Obtener el ID del video seleccionado previamente
					const selectedVideoId = $('input[name=video_selected_vimeo]').val();

					// Si hay un video seleccionado previamente, seleccionarlo en el modal
					if (selectedVideoId) {
						const selection = mediaFrame.state().get('selection');
						const selectedAttachment = attachments.get(selectedVideoId);
						if (selectedAttachment) {
							selection.add(selectedAttachment);
						}
					}
				});

				mediaFrame.on('select', function () {
					const selection = mediaFrame.state().get('selection');
					selection.each(function (attachment) {
						const attachmentId = attachment.id;
						const attachmentUrl = attachment.attributes.url;
						const attachmentEmbed = attachment.attributes.embed;
						$('input[name=video_selected_vimeo]').val(attachmentId);
						$('input[name=video_selected_vimeo_url]').val(attachmentUrl);
						$('input[name=video_selected_vimeo_embed]').val(attachmentEmbed);
						$('#vimeo-embed').html(attachmentEmbed);
					});
				});
				;

				mediaFrame.open();
			} catch (error) {
				console.error('Error al cargar la galería de medios:', error);
			}
		});
	});

})

