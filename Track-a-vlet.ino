// Include & enable library functions
#include <TinyGPS++.h>
#include <SoftwareSerial.h>
#include <lmic.h>
#include <hal/hal.h>
#include <SPI.h>

// Set softserial for Uno board comment for Mega board
SoftwareSerial Serial1(3, 4); // RX, TX

// THE TINGS NETWORK
static const u4_t DEVADDR = 0x26011708; 
static const u1_t PROGMEM NWKSKEY[16] = { 0x65, 0x16, 0xE6, 0xF0, 0xB0, 0xFA, 0x04, 0xFD, 0x88, 0x0B, 0x6A, 0x3B, 0x0F, 0x0C, 0x65, 0xC9 };
static const u1_t PROGMEM APPSKEY[16] = { 0xA9, 0xC7, 0x58, 0x28, 0x80, 0x47, 0xC1, 0x4E, 0xCD, 0x64, 0x92, 0x3C, 0xE0, 0x12, 0x45, 0xC8 };
void os_getArtEui (u1_t* buf) { }
void os_getDevEui (u1_t* buf) { }
void os_getDevKey (u1_t* buf) { }

// Set vlet number : 01 = 1235 / 02 = 384 / 03 = 283 / 04 = 014
int vlet = 04; 

// Pin mapping for RFM9X
const lmic_pinmap lmic_pins = 
{
    .nss = 10,
    .rxtx = LMIC_UNUSED_PIN,
    .rst = LMIC_UNUSED_PIN,
    .dio = {2, 6, 7},
};
 
// Restrict to channel0 and SF7 if uncommented; otherwise all channels & SF12
#define CHANNEL0

// Other
byte Transmitted = 0;
byte Event_Timeout = 0;
byte counter = 0;
int_fast32_t prevtimepassed = millis(); // int to hold the arduino miilis since startup

// The TinyGPS++ object
TinyGPSPlus gps;

// Setup for startup
void setup() 
{
  Serial.begin(9600);
  // Init GPS
  Serial1.begin(9600);
  // Display channel info
  #define CHANNEL0
}

// Main
void loop() 
{
  bool gpsupdate = false;
  while (Serial1.available() > 0)
  {
    gpsupdate = gps.encode(Serial1.read());
  }

int_fast32_t timepassed = millis(); // int to hold the arduino milis since startup
if ( prevtimepassed + 30000 < timepassed )
  {
   prevtimepassed = timepassed;
  #if SENDSTRING
    String Data = String("PING");
    // Transmit the combined message (string):
    TX_Data(Data);
  #else
  uint8_t mydata[10];
  mydata[0] = vlet;
  mydata[1] = counter++;
  if (gps.location.isValid())
  {
    //int32_t lat =   gps.location.lat() * 100000;
    //int32_t lon =   gps.location.lng() * 100000;
    float lat_raw =   gps.location.lat();
    float lon_raw = gps.location.lng();
    int32_t lat =   lat_raw * 100000;
    int32_t lon = lon_raw * 100000;
    // Pad 2 int32_t to 6 8uint_t, skipping the last byte (x >> 24)
    mydata[2] = (uint8_t)(lat);
    mydata[3] = (uint8_t)(lat >> 8);
    mydata[4] = (uint8_t)(lat >> 16);
    mydata[5] = (uint8_t)(lat >> 24);    
    mydata[6] = (uint8_t)(lon);
    mydata[7] = (uint8_t)(lon >> 8);
    mydata[8] = (uint8_t)(lon >> 16);
    mydata[9] = (uint8_t)(lon >> 24);
  }
    TX_Data(mydata, sizeof(mydata));
  #endif
    // Wait for response of the queued message (check if message is send correctly)
      os_runloop_once();
      // Continue until message is transmitted correctly
      while(Transmitted != 1) 
      {
       os_runloop_once();
        // Add timeout counter when nothing happens:
        Event_Timeout++;
        if (Event_Timeout >= 60) 
        {
          // Timeout when there's no "EV_TXCOMPLETE" event after 60 seconds
          Serial.println("\tEvent Timeout, message not transmitted correctly\n");
          break;
        }

      }
      Transmitted = 0;
      Event_Timeout = 0;
  }
}

// Transmit LoRaWAN message:
void TX_DataString(String New_Message)
{
      const uint8_t StringLength = New_Message.length() + 1;
      uint8_t mydata[StringLength];
      // Transform message depending on the length:
      for(int i = 0; i <= StringLength; i++)
      {
        mydata[i] = New_Message.charAt(i);
      }
      TX_Data(mydata, StringLength);
}

void TX_Data(uint8_t * mydata, uint8_t length)
{
      // Init LMIC
      os_init();
      // Reset the MAC state. Session and pending data transfers will be discarded.
      LMIC_reset();
      // LMIC setup
      #ifdef PROGMEM
      uint8_t appskey[sizeof(APPSKEY)];
      uint8_t nwkskey[sizeof(NWKSKEY)];
      memcpy_P(appskey, APPSKEY, sizeof(APPSKEY));
      memcpy_P(nwkskey, NWKSKEY, sizeof(NWKSKEY));
      LMIC_setSession (0x1, DEVADDR, nwkskey, appskey);
      #else
      LMIC_setSession (0x1, DEVADDR, NWKSKEY, APPSKEY);
      #endif

      // Channel config
      LMIC_setupChannel(0, 868100000, DR_RANGE_MAP(DR_SF12, DR_SF7),  BAND_CENTI);
      LMIC_setupChannel(1, 868300000, DR_RANGE_MAP(DR_SF12, DR_SF7B), BAND_CENTI);
      LMIC_setupChannel(2, 868500000, DR_RANGE_MAP(DR_SF12, DR_SF7),  BAND_CENTI);
      LMIC_setupChannel(3, 867100000, DR_RANGE_MAP(DR_SF12, DR_SF7),  BAND_CENTI);
      LMIC_setupChannel(4, 867300000, DR_RANGE_MAP(DR_SF12, DR_SF7),  BAND_CENTI);
      LMIC_setupChannel(5, 867500000, DR_RANGE_MAP(DR_SF12, DR_SF7),  BAND_CENTI);
      LMIC_setupChannel(6, 867700000, DR_RANGE_MAP(DR_SF12, DR_SF7),  BAND_CENTI);
      LMIC_setupChannel(7, 867900000, DR_RANGE_MAP(DR_SF12, DR_SF7),  BAND_CENTI);
      LMIC_setupChannel(8, 868800000, DR_RANGE_MAP(DR_FSK,  DR_FSK),  BAND_MILLI);

      // For single channel gateways: Restrict to channel 0 when defined above
      #ifdef CHANNEL0
      LMIC_disableChannel(1);
      LMIC_disableChannel(2);
      LMIC_disableChannel(3);
      LMIC_disableChannel(4);
      LMIC_disableChannel(5);
      LMIC_disableChannel(6);
      LMIC_disableChannel(7);
      LMIC_disableChannel(8);
      #endif

      // Disable link check validation
      LMIC_setLinkCheckMode(0);

      // Set data rate and transmit power
      LMIC_setDrTxpow(DR_SF7,14);

      // Check if there is not a current TX/RX job running before transmitting
      if (LMIC.opmode & OP_TXRXPEND)
      {
        Serial.println(F("OP_TXRXPEND, not sending"));
      }
      else
      {
        // Send message
        LMIC_setTxData2(1, mydata, length, 0);
      }
}

// When something happens after transmitting (status check):

void onEvent (ev_t ev) 
{
    Serial.print("\tEvent: ");
    switch(ev) 
    {
        case EV_SCAN_TIMEOUT:
            Serial.println(F("EV_SCAN_TIMEOUT"));
            break;
        case EV_BEACON_FOUND:
            Serial.println(F("EV_BEACON_FOUND"));
            break;
        case EV_BEACON_MISSED:
            Serial.println(F("EV_BEACON_MISSED"));
            break;
        case EV_BEACON_TRACKED:
            Serial.println(F("EV_BEACON_TRACKED"));
            break;
        case EV_JOINING:
            Serial.println(F("EV_JOINING"));
            break;
        case EV_JOINED:
            Serial.println(F("EV_JOINED"));
            break;
        case EV_RFU1:
            Serial.println(F("EV_RFU1"));
            break;
        case EV_JOIN_FAILED:
            Serial.println(F("EV_JOIN_FAILED"));
            break;
        case EV_REJOIN_FAILED:
            Serial.println(F("EV_REJOIN_FAILED"));
            break;
        case EV_TXCOMPLETE:
            // Message transmitted
            Serial.println(F("EV_TXCOMPLETE (Packet send)"));
            // Set "transmitted OK" status high
            Transmitted = 1;
            // Data received in RX slot after transmission
            if(LMIC.dataLen) 
            {
                Serial.print(F("Data Received: "));
                Serial.write(LMIC.frame+LMIC.dataBeg, LMIC.dataLen);
                Serial.println();
            }
            break;
        case EV_LOST_TSYNC:
            Serial.println(F("EV_LOST_TSYNC"));
            break;
        case EV_RESET:
            Serial.println(F("EV_RESET"));
            break;
        case EV_RXCOMPLETE:
            // Data received in ping slot
            Serial.println(F("EV_RXCOMPLETE"));
            break;
        case EV_LINK_DEAD:
            Serial.println(F("EV_LINK_DEAD"));
            break;
        case EV_LINK_ALIVE:
            Serial.println(F("EV_LINK_ALIVE"));
            break;
         default:
            Serial.println(F("Unknown event"));
            break;
    }
    Serial.println();
}
