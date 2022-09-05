package util

import (
	"bytes"
	"encoding/binary"
	"encoding/json"
	"fmt"
	"log"
	"math/rand"
	"strconv"
	"time"
)

// Int64ToByte converts an int64 to bytes array in BigEndian
func Int64ToByte(data int64) []byte {
	buf := new(bytes.Buffer)
	_ = binary.Write(buf, binary.BigEndian, data)
	return buf.Bytes()
}

// ByteToInt64 converts an int64 in bytes to int64 with BigEndian
func ByteToInt64(data []byte) int64 {
	var value int64
	buf := bytes.NewReader(data)
	_ = binary.Read(buf, binary.BigEndian, &value)
	return value
}

func ToFloat64(v interface{}) float64 {
	if v == nil {
		return 0.0
	}

	switch v.(type) {
	case float64:
		return v.(float64)
	case string:
		vStr := v.(string)
		vF, _ := strconv.ParseFloat(vStr, 64)
		return vF
	default:
		//panic("to float64 error.")
		return 0
	}
}

func ToInt(value interface{}) (i int) {
	switch v := value.(type) {
	case bool:
		i = 1
		if v {
			i = 0
		}
	case float32:
		i = int(v)
	case float64:
		i = int(v)
	case int:
		i = v
	case int8:
		i = int(v)
	case int16:
		i = int(v)
	case int32:
		i = int(v)
	case int64:
		i = int(v)
	case uint:
		i = int(v)
	case uint8:
		i = int(uint(v))
	case uint16:
		i = int(uint(v))
	case uint32:
		i = int(uint(v))
	case uint64:
		i = int(uint(v))
	case string:
		i, _ = strconv.Atoi(v)
	case []byte:
		i = int(ByteToInt64(v))
	default:
		str := fmt.Sprintf("%d", v)
		i, _ = strconv.Atoi(str)
	}
	return i
}

// Convert any type to string.
func ToString(value interface{}, args ...int) (s string) {
	switch v := value.(type) {
	case bool:
		s = strconv.FormatBool(v)
	case float32:
		s = strconv.FormatFloat(float64(v), 'f', argInt(args).Get(0, -1), argInt(args).Get(1, 32))
	case float64:
		s = strconv.FormatFloat(v, 'f', argInt(args).Get(0, -1), argInt(args).Get(1, 64))
	case int:
		s = strconv.FormatInt(int64(v), argInt(args).Get(0, 10))
	case int8:
		s = strconv.FormatInt(int64(v), argInt(args).Get(0, 10))
	case int16:
		s = strconv.FormatInt(int64(v), argInt(args).Get(0, 10))
	case int32:
		s = strconv.FormatInt(int64(v), argInt(args).Get(0, 10))
	case int64:
		s = strconv.FormatInt(v, argInt(args).Get(0, 10))
	case uint:
		s = strconv.FormatUint(uint64(v), argInt(args).Get(0, 10))
	case uint8:
		s = strconv.FormatUint(uint64(v), argInt(args).Get(0, 10))
	case uint16:
		s = strconv.FormatUint(uint64(v), argInt(args).Get(0, 10))
	case uint32:
		s = strconv.FormatUint(uint64(v), argInt(args).Get(0, 10))
	case uint64:
		s = strconv.FormatUint(v, argInt(args).Get(0, 10))
	case string:
		s = v
	case []byte:
		s = string(v)
	default:
		s = fmt.Sprintf("%v", v)
	}
	return s
}

type argInt []int

func (a argInt) Get(i int, args ...int) (r int) {
	if i >= 0 && i < len(a) {
		r = a[i]
	} else if len(args) > 0 {
		r = args[0]
	}
	return
}

func ToJsonString(input interface{}) string {
	js, err := json.Marshal(input)
	if err != nil {
		log.Println("ToJsonString error:", err.Error())
	}
	return string(js)
}

var letterBytes = []byte("0123456789abcdefghijklmnopqrstuvwxyz")

// GenRandomBytes 以当前时间为种子，生成一个指定长度的字符数组
func GenRandomBytes(length int) []byte {
	// 为了避免连续调用导致形成完全一样的种子，在时间的基础上再加一个随机数
	r := rand.New(rand.NewSource(time.Now().UnixNano() + rand.Int63()))
	b := make([]byte, length)
	l := len(letterBytes)
	for i := range b {
		b[i] = letterBytes[r.Intn(l)]
	}
	return b
}
