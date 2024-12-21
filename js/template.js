
        document.addEventListener('DOMContentLoaded', function() {
            function addEventListeners() {
                var button = document.querySelector('.TVButtonWaiter.TVTourBuySendingButton.TVButtonHover.TVColorGreen500.TVSize-S.TVFontSize-S.TVSendingBuyButton');
                if (button) {
                    button.addEventListener('click', function() {
                        var nameInput = document.querySelector('.TVCustomerContactsName input[type="text"]');
                        var phoneInput = document.querySelector('.TVUserFormInputPhone input[type="tel"]');
                        var emailInput = document.querySelector('.TVCustomerContactsEmail input[type="text"]');

                        var name = nameInput ? nameInput.value : '';
                        var phone = phoneInput ? phoneInput.value : '';
                        var email = emailInput ? emailInput.value : '';

						const tourHeaders = document.querySelectorAll('.TVTourCardOptionHeader');
						const tourFooters = document.querySelectorAll('.TVTourCardOptionFooter');
						const resortElem = document.querySelector('.TVHotelTitleResort');
						const hotelElem = document.querySelector('.TVHotelTitleName');
                        const price = document.querySelector('.TVTourCardInfoContent .TVTourCardPriceValue');

                        const comment = "Заявка с сайта. Форма TourVisor. Данные заказа: " +
							" Вылет: " + (tourHeaders[1]?.textContent ?? 'нет данных') + ", " + (tourFooters[1]?.textContent ?? 'нет данных') +
							" Город вылета: " + (tourFooters[0]?.textContent ?? 'нет данных') +
							" Страна: " + (resortElem?.textContent ?? 'нет данных') +
							" Отель: " + (hotelElem?.textContent ?? 'нет данных') +
							" Питание: " + (tourFooters[2]?.textContent ?? 'нет данных') +
							" Номер: " + (tourHeaders[3]?.textContent ?? 'нет данных') +
							" Размещение: " + (tourFooters[3]?.textContent ?? 'нет данных') +
                            " Цена: " + price.textContent;

                        const data = {
                            leadName: 'Заявка с сайта. Форма TourVisor',
                            text: comment,
                            name: name,
                            phone: phone,
                            email: email,
                            is_skip_sending: '0',
                            fields: {
                                140495: '{landingPage}',
                                140520: '{source}',
                                140521: '{city}',
                                140538: '{utmSource}',
                                140539: '{utmMedium}',
                                140540: '{utmCampaign}',
                                140541: '{utmTerm}',
                                140542: '{utmContent}',
                            }
                        };
                    });
                }
            }
            const observer = new MutationObserver(function(mutations) {
                addEventListeners();
            });
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            addEventListeners();
        });
   

   
        document.addEventListener('DOMContentLoaded', function() {
            function addEventListenersSecond() {
                var button = document.querySelector('.TVRequestFormSendButton');
                if (button) {
                    button.addEventListener('click', function () {
                        var nameInputs = document.querySelectorAll('.TVCustomerContactsName input[type="text"], .TVUserFormInputName input[type="text"]');
                        var nameInput = nameInputs.length > 1 ? nameInputs[1] : nameInputs[0];
                        var name = nameInput ? nameInput.value : '';

                        var phoneInputs = document.querySelectorAll('.TVUserFormInputPhone input[type="tel"], .TVUserFormInputPhone input[type="tel"]');
                        var phoneInput = phoneInputs.length > 1 ? phoneInputs[1] : phoneInputs[0];
                        var phone = phoneInput ? phoneInput.value : '';

                        var emailInputs = document.querySelectorAll('.TVCustomerContactsEmail input[type="text"], .TVUserFormInputMail input[type="text"]');
                        var emailInput = emailInputs.length > 1 ? emailInputs[1] : emailInputs[0];
                        var email = emailInput ? emailInput.value : '';

						const tourHeaders = document.querySelectorAll('.TVTourCardOptionHeader');
						const tourFooters = document.querySelectorAll('.TVTourCardOptionFooter');
						const resortElem = document.querySelector('.TVHotelTitleResort');
						const hotelElem = document.querySelector('.TVHotelTitleName');
                        const commentInput = document.querySelector('.TVCustomerContactsComment');
                        const price = document.querySelector('.TVTourCardInfoContent .TVTourCardPriceValue');
                        let comment = "";

                        if (tourHeaders.length > 0 && tourFooters.length > 0) {
                            comment = "Заявка с сайта. Форма TourVisor. Данные заказа: " +
                                " Вылет: " + (tourHeaders[1]?.textContent ?? 'нет данных') + ", " + (tourFooters[1]?.textContent ?? 'нет данных') +
                                " Город вылета: " + (tourFooters[0]?.textContent ?? 'нет данных') +
                                " Страна: " + (resortElem?.textContent ?? 'нет данных') +
                                " Отель: " + (hotelElem?.textContent ?? 'нет данных') +
                                " Питание: " + (tourFooters[2]?.textContent ?? 'нет данных') +
                                " Номер: " + (tourHeaders[3]?.textContent ?? 'нет данных') +
                                " Размещение: " + (tourFooters[3]?.textContent ?? 'нет данных') +
                                " Цена: " + price.textContent;
                        }
                        else {
                            comment = "Заявка с сайта. Форма TourVisor. " +
                                (commentInput?.value ?? 'нет данных');
                        }

                        const data = {
                            leadName: 'Заявка с сайта. Форма TourVisor',
                            text: comment,
                            name: name,
                            phone: phone,
                            email: email,
                            is_skip_sending: '0',
                            fields: {
                                140495: '{landingPage}',
                                140520: '{source}',
                                140521: '{city}',
                                140538: '{utmSource}',
                                140539: '{utmMedium}',
                                140540: '{utmCampaign}',
                                140541: '{utmTerm}',
                                140542: '{utmContent}',
                            }
                        };
                    });
                }
            }
            const observer = new MutationObserver(function(mutations) {
                addEventListenersSecond();
            });
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
            addEventListenersSecond();
        });